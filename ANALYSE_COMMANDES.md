# Analyse de la Logique de Gestion des Commandes (En Gros / En Particulier)

## Vue d'ensemble

Le fichier `commander.html` gère deux types de ventes :
- **Vente Particulière (Retail)** : Prix par pièce ou par gramme pour les particuliers
- **Vente en Gros (Wholesale)** : Prix par pièce pour les commandes importantes

---

## 1. Structure des Données

### 1.1 Variable de Type de Vente
```javascript
let saleType = 'retail'; // 'retail' ou 'wholesale'
```

### 1.2 Structure des Produits
Chaque produit possède deux prix :
```javascript
const products = {
    'graines-noires': { 
        name: 'Graines noires', 
        weight: 20,              // grammes par pièce
        wholesalePrice: 2,        // DH par pièce (gros)
        retailPrice: 2.5          // DH par pièce (particulier)
    },
    // ... autres produits
};
```

### 1.3 Modes de Quantité par Produit
```javascript
let productModes = {}; // 'pieces' ou 'grams' pour chaque produit
```

**Mode par défaut** : `'pieces'` (initialisé automatiquement)

---

## 2. Fonctionnalités Principales

### 2.1 Sélection du Type de Vente (`setSaleType`)

**Fonction** : `setSaleType(type)`

**Comportement** :
1. Met à jour `saleType` global
2. Réinitialise le panier (vide toutes les quantités)
3. Met à jour les boutons visuels (active/désactive)
4. Recalcule tous les prix affichés
5. Met à jour le résumé de commande
6. Met à jour la section paiement échelonné
7. Met à jour le texte de minimum de commande

**Code clé** :
```javascript
function setSaleType(type) {
    saleType = type;
    // Réinitialiser le panier
    Object.keys(cart).forEach(productId => {
        cart[productId] = 0;
    });
    // Mettre à jour l'affichage
    updateAllPrices();
    updateAllProductsDisplay();
    updateOrderSummary();
    updateInstallmentSection();
    updateMinOrderText();
}
```

---

### 2.2 Calcul des Prix (`getPrice`)

**Fonction** : `getPrice(productId)`

**Logique** :
- **Vente en Gros** : Utilise `wholesalePrice` par pièce
- **Vente Particulière** : Utilise `retailPrice` par pièce
- **Conversion devise** : Multiplie par `exchangeRates[currentCurrency]`

```javascript
function getPrice(productId) {
    if (saleType === 'wholesale') {
        const basePrice = products[productId].wholesalePrice || 0;
        return basePrice * exchangeRates[currentCurrency];
    } else {
        const basePrice = products[productId].retailPrice || 0;
        return basePrice * exchangeRates[currentCurrency];
    }
}
```

---

### 2.3 Modes de Quantité (Pièces vs Grammes)

#### 2.3.1 Initialisation (`initializeProductMode`)
```javascript
function initializeProductMode(productId) {
    if (!productModes[productId]) {
        productModes[productId] = 'pieces'; // Mode par défaut
    }
}
```

#### 2.3.2 Changement de Mode (`setQuantityMode`)
```javascript
function setQuantityMode(productId, mode) {
    const currentValue = cart[productId] || 0;
    
    // Conversion automatique lors du changement de mode
    if (mode === 'pieces' && productModes[productId] === 'grams') {
        // Convertir grammes → pièces
        cart[productId] = Math.ceil(currentValue / product.weight);
    } else if (mode === 'grams' && productModes[productId] === 'pieces') {
        // Convertir pièces → grammes
        cart[productId] = currentValue * product.weight;
    }
    
    productModes[productId] = mode;
    updateProductDisplay(productId);
}
```

#### 2.3.3 Calcul du Prix selon le Mode

**En mode "pièces"** :
```javascript
if (mode === 'pieces') {
    if (saleType === 'wholesale') {
        subtotal = quantity * wholesalePrice * exchangeRate;
    } else {
        subtotal = quantity * retailPrice * exchangeRate;
    }
}
```

**En mode "grammes"** :
```javascript
else { // mode === 'grams'
    const weight = product.weight; // poids d'une pièce
    if (saleType === 'wholesale') {
        const pricePerGram = wholesalePrice / weight;
        subtotal = quantityInGrams * pricePerGram * exchangeRate;
    } else {
        const pricePerGram = retailPrice / weight;
        subtotal = quantityInGrams * pricePerGram * exchangeRate;
    }
}
```

---

### 2.4 Seuils Minimums de Commande

**Fonction** : `updateMinOrderText()`

**Seuils** :
- **Vente en Gros** : 500 DH minimum
- **Vente Particulière** : 100 DH minimum (50g minimum)

```javascript
function updateMinOrderText() {
    if (saleType === 'wholesale') {
        minOrderAmount = 500 * exchangeRates[currentCurrency];
        minOrderText = `Commande minimum : ${minOrderAmount} (vente en gros)`;
    } else {
        minOrderAmount = 100 * exchangeRates[currentCurrency];
        minOrderText = `Commande minimum : ${minOrderAmount} (50g minimum)`;
    }
}
```

**Validation** : Le bouton "Commander" est désactivé si :
- Le total < minimum requis
- Le formulaire client n'est pas valide
- Aucun produit dans le panier

---

### 2.5 Paiement Échelonné (Uniquement en Gros)

**Fonction** : `updateInstallmentSection()`

**Disponibilité** :
- ✅ **Visible uniquement** pour `saleType === 'wholesale'`
- ✅ **Minimum** : 500 DH
- ✅ Permet de diviser le paiement en plusieurs échéances

**Logique** :
```javascript
function updateInstallmentSection() {
    if (saleType === 'wholesale') {
        installmentSection.style.display = 'block';
        // Calcule le montant par échéance
        const amountPerInstallment = total / numberOfInstallments;
    } else {
        installmentSection.style.display = 'none';
    }
}
```

---

### 2.6 Calcul du Résumé de Commande (`updateOrderSummary`)

**Fonction** : `updateOrderSummary()`

**Processus** :
1. Parcourt tous les produits du panier
2. Pour chaque produit avec quantité > 0 :
   - Détermine le mode (pièces/grammes)
   - Calcule le sous-total selon le type de vente
   - Formate le texte de quantité
3. Additionne tous les sous-totaux
4. Met à jour l'affichage
5. Valide le minimum de commande
6. Active/désactive le bouton WhatsApp

**Exemple de calcul** :
```javascript
// Mode pièces
if (mode === 'pieces') {
    if (saleType === 'wholesale') {
        subtotal = quantity * wholesalePrice * exchangeRate;
        quantityText = `${quantity} pièce(s) (${quantity * weight}g)`;
    } else {
        subtotal = quantity * retailPrice * exchangeRate;
        quantityText = `${quantity} pièce(s) (${quantity * weight}g)`;
    }
}
// Mode grammes
else {
    const pricePerGram = (saleType === 'wholesale' ? wholesalePrice : retailPrice) / weight;
    subtotal = quantityInGrams * pricePerGram * exchangeRate;
    quantityText = `${quantityInGrams}g`;
}
```

---

### 2.7 Envoi vers WhatsApp (`sendToWhatsApp`)

**Fonction** : `sendToWhatsApp()`

**Contenu du message** :
1. En-tête de commande
2. Liste des produits avec :
   - Nom du produit
   - Quantité (pièces ou grammes)
   - Sous-total par produit
3. Total général
4. **Si vente en gros** : Détails du paiement échelonné
5. Informations client :
   - Nom, Email, Téléphone
   - Date de livraison souhaitée
   - Adresse de livraison
   - Notes optionnelles

**Format exemple** :
```
🌰 *Commande BeldiNuts*

Bonjour ! Je souhaite commander les produits suivants :

• Graines noires: 5 pièces (100g) (10.00 DH)
• Amandes: 200g (12.00 DH)

*Total: 22.00 DH*

💳 Paiement Échelonné :
📊 Nombre d'échéances: 3
💰 Montant par échéance: 7.33 DH

*Informations de livraison :*
👤 Nom: [Nom]
📧 Email: [Email]
...
```

---

## 3. Flux Utilisateur

### 3.1 Vente Particulière (Retail)
1. Utilisateur clique sur "Vente Particulière"
2. Prix affichés : `retailPrice` par pièce
3. Minimum : 100 DH (50g minimum)
4. Pas de paiement échelonné
5. Calcul : prix par pièce ou par gramme selon le mode

### 3.2 Vente en Gros (Wholesale)
1. Utilisateur clique sur "Vente en Gros"
2. Prix affichés : `wholesalePrice` par pièce (moins cher)
3. Minimum : 500 DH
4. Paiement échelonné disponible
5. Calcul : prix par pièce ou par gramme selon le mode

---

## 4. Points Clés de la Logique

### 4.1 Réinitialisation du Panier
⚠️ **Important** : Quand on change de type de vente, le panier est **complètement vidé**. Cela évite les incohérences de prix.

### 4.2 Conversion Automatique
Quand on change de mode (pièces ↔ grammes), la quantité est **automatiquement convertie** :
- Grammes → Pièces : `Math.ceil(grams / weight)`
- Pièces → Grammes : `pieces * weight`

### 4.3 Calcul du Prix en Grammes
Le prix par gramme est calculé en **divisant le prix par pièce par le poids d'une pièce** :
```javascript
pricePerGram = pricePerPiece / weightPerPiece
```

### 4.4 Support Multi-Devises
Tous les prix sont convertis via `exchangeRates[currentCurrency]` :
- MAD (DH)
- EUR (€)
- USD ($)

---

## 5. Structure HTML Clé

### 5.1 Sélecteur de Type de Vente
```html
<button id="retail-btn" onclick="setSaleType('retail')">
    Vente Particulière
    <div>Prix par gramme</div>
</button>
<button id="wholesale-btn" onclick="setSaleType('wholesale')">
    Vente en Gros
    <div>Prix par pièce</div>
</button>
```

### 5.2 Toggle Mode Quantité (par produit)
```html
<button onclick="setQuantityMode('productId', 'pieces')">
    Par pièce
</button>
<button onclick="setQuantityMode('productId', 'grams')">
    Grammes personnalisés
</button>
```

### 5.3 Section Paiement Échelonné
```html
<div id="installment-section" style="display: none;">
    <!-- Visible uniquement si saleType === 'wholesale' -->
</div>
```

---

## 6. Résumé des Différences

| Aspect | Vente Particulière | Vente en Gros |
|--------|-------------------|---------------|
| **Prix** | `retailPrice` | `wholesalePrice` (moins cher) |
| **Minimum** | 100 DH (50g) | 500 DH |
| **Paiement échelonné** | ❌ Non | ✅ Oui |
| **Description** | "Prix par gramme" | "Prix par pièce" |
| **Usage** | Particuliers | Grossistes |

---

## 7. Fonctions Utilitaires

- `updateAllPrices()` : Met à jour tous les prix affichés
- `updateAllProductsDisplay()` : Met à jour l'affichage de tous les produits
- `updateProductDisplay(productId)` : Met à jour l'affichage d'un produit
- `increaseQuantity(productId)` : Incrémente la quantité
- `decreaseQuantity(productId)` : Décrémente la quantité
- `setSuggestedQuantity(productId, grams)` : Définit une quantité suggérée en grammes
- `setSuggestedPieces(productId, pieces)` : Définit un nombre de pièces suggéré

---

## Conclusion

La logique de gestion des commandes est bien structurée avec :
- ✅ Séparation claire entre vente en gros et particulière
- ✅ Support de deux modes de quantité (pièces/grammes)
- ✅ Conversion automatique entre modes
- ✅ Validation des seuils minimums
- ✅ Paiement échelonné pour les gros clients
- ✅ Support multi-devises
- ✅ Réinitialisation sécurisée lors du changement de type


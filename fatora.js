
let totalAmount = 0; // for total amount

function updateTotalAmount() {
    document.querySelector('#totalAmount').textContent = `${totalAmount}`;
    document.querySelector('#totalInput').value = totalAmount; // to send to PHP
}

function addToFatora(itemName, itemPrice) {
    const lastOrder = document.querySelector('#items');

    // Dynamic element creation
    const itemDiv = document.createElement('div');
    itemDiv.className = 'd-flex align-items-center justify-content-between';

    const nameDiv = document.createElement('div');
    const nameElement = document.createElement('strong');
    nameElement.textContent = itemName;
    nameDiv.appendChild(nameElement);

    const quantityDiv = document.createElement('div');
    quantityDiv.className = 'd-flex align-items-center ms-2 mt-1';

    const btnMinus = document.createElement('button');
    btnMinus.className = 'btn btn-warning me-2';
    btnMinus.innerHTML = '<i class="fas fa-minus"></i>';
    btnMinus.type = 'button';
    const quantityInput = document.createElement('input');
    quantityInput.type = 'number';
    quantityInput.value = '1';
    quantityInput.min = '1';
    quantityInput.className = 'form-control me-2';

    const btnPlus = document.createElement('button');
    btnPlus.type = 'button';
    btnPlus.className = 'btn btn-primary';
    btnPlus.innerHTML = '<i class="fas fa-plus"></i>';

    quantityDiv.appendChild(btnMinus);
    quantityDiv.appendChild(quantityInput);
    quantityDiv.appendChild(btnPlus);

    const priceDiv = document.createElement('div');
    const priceSpan = document.createElement('span');
    priceSpan.textContent = `${itemPrice}`;
    priceDiv.appendChild(priceSpan);

    const btnDel = document.createElement('button');
    btnDel.className = 'btn btn-danger';
    btnDel.innerHTML = '<i class="fas fa-times"></i>';
    btnDel.type = 'button';

    btnMinus.addEventListener('click', () => {
        let currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            currentValue -= 1;
            quantityInput.value = currentValue;
            updatePrice();
        }
    });

    btnPlus.addEventListener('click', () => {
        let currentValue = parseInt(quantityInput.value);
        currentValue += 1;
        quantityInput.value = currentValue;
        updatePrice();
    });

    btnDel.addEventListener('click', () => {
        totalAmount -= parseInt(priceSpan.textContent);
        updateTotalAmount();
        lastOrder.removeChild(itemDiv);
    });

    // btnDel.addEventListener('click', () => {
    //     totalAmount -= parseInt(priceSpan.textContent);
    //     updateTotalAmount();
    //     lastOrder.removeChild(itemDiv);
        
    //     // Remove the corresponding image from "Latest Order"
    //     const lastOrderImages = document.getElementById('lastorder').getElementsByTagName('img');
    //     for (let i = 0; i < lastOrderImages.length; i++) {
    //         if (lastOrderImages[i].alt === itemName) { // Assuming itemName is unique
    //             document.getElementById('lastorder').removeChild(lastOrderImages[i]);
    //             break;
    //         }
    //     }
    // });



    itemDiv.appendChild(nameDiv);
    itemDiv.appendChild(quantityDiv);
    itemDiv.appendChild(priceDiv);
    itemDiv.appendChild(btnDel);

    lastOrder.appendChild(itemDiv);
    updatePrice();
    // Creating hidden inputs to send data to PHP
    const inputForName = document.createElement('input');
    inputForName.type = 'hidden';
    inputForName.name = 'itemname[]';
    inputForName.value = itemName;

    const inputForQuantity = document.createElement('input');
    inputForQuantity.type = 'hidden';
    inputForQuantity.value = '1';
    inputForQuantity.name = 'itemQuantity[]';

    const inputForPrice = document.createElement('input');
    inputForPrice.type = 'hidden';
    inputForPrice.value = itemPrice;
    inputForPrice.name = 'itemprice[]';

    itemDiv.appendChild(inputForPrice);
    itemDiv.appendChild(inputForQuantity);
    itemDiv.appendChild(inputForName);

    quantityInput.addEventListener('change', () => {
        inputForQuantity.value = quantityInput.value;
        updatePrice();
    });

    function updatePrice() {
        const quantity = parseInt(quantityInput.value);
        const totalPrice = quantity * itemPrice;
        priceSpan.textContent = `${totalPrice}`;
        calculateTotalAmount();
    }

    function calculateTotalAmount() {
        totalAmount = Array.from(lastOrder.children).reduce((total, item) => {
            const priceSpan = item.querySelector('div:nth-child(3) span');
            total += parseInt(priceSpan.textContent);
            return total;
        }, 0);
        updateTotalAmount();
    }
}

function addToLastOrder(imagePath) {
    const lastOrder = document.getElementById('lastorder');
    const imgElement = document.createElement('img');
    // imgElement.alt = itemName; // Set alt to itemName
    imgElement.src = imagePath;
    imgElement.style.width = '100px';
    imgElement.style.height = '80px';
    imgElement.classList.add('m-1');
    lastOrder.appendChild(imgElement);
}

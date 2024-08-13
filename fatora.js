
let totalAmount = 0;

function updateTotalAmount() {
    document.querySelector('#totalAmount').textContent = `${totalAmount} EGP`;
}

function addToFatora(itemName, itemPrice) {
    const lastOrder = document.querySelector('#items');
    
    // Create the div for the item
    const itemDiv = document.createElement('div');
    itemDiv.className = 'd-flex align-items-center justify-content-between';

    // Create and add the name element
    const nameDiv = document.createElement('div');
    const nameElement = document.createElement('strong');
    nameElement.textContent = itemName;
    nameDiv.appendChild(nameElement);

    // Create and add the quantity elements
    const quantityDiv = document.createElement('div');
    quantityDiv.className = 'd-flex align-items-center ms-2 mt-1';

    const btnMinus = document.createElement('button');
    btnMinus.className = 'btn btn-warning me-2';
    btnMinus.innerHTML = '<i class="fas fa-minus"></i>';

    const quantityInput = document.createElement('input');
    quantityInput.type = 'number';
    quantityInput.value = '1';
    quantityInput.min = '1';
    quantityInput.className = 'form-control me-2';

    const btnPlus = document.createElement('button');
    btnPlus.className = 'btn btn-primary';
    btnPlus.innerHTML = '<i class="fas fa-plus"></i>';

    quantityDiv.appendChild(btnMinus);
    quantityDiv.appendChild(quantityInput);
    quantityDiv.appendChild(btnPlus);

    // Create and add the price element
    const priceDiv = document.createElement('div');
    const priceSpan = document.createElement('span');
    priceSpan.textContent = `${itemPrice} EGP`;
    priceDiv.appendChild(priceSpan);

    // Create and add the delete button
    const btnDel = document.createElement('button');
    btnDel.className = 'btn btn-danger';
    btnDel.innerHTML = '<i class="fas fa-times"></i>';

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

    itemDiv.appendChild(nameDiv);
    itemDiv.appendChild(quantityDiv);
    itemDiv.appendChild(priceDiv);
    itemDiv.appendChild(btnDel);

    lastOrder.appendChild(itemDiv);
    updatePrice();

    function updatePrice() {
        const quantity = parseInt(quantityInput.value);
        const totalPrice = quantity * itemPrice;
        priceSpan.textContent = `${totalPrice} EGP`;
        calculateTotalAmount();
    }

    function calculateTotalAmount() {
        totalAmount = Array.from(lastOrder.children).reduce((total, item) => {
            const priceSpan = item.querySelector('div:nth-child(3) span');
            return total + parseInt(priceSpan.textContent);
        }, 0);
        updateTotalAmount();
    }
}

function addToLastOrder(imagePath) {
    const lastOrder = document.getElementById('lastorder');
    const imgElement = document.createElement('img');
    imgElement.src = imagePath;
    imgElement.classList.add('m-1');
    lastOrder.appendChild(imgElement);
}


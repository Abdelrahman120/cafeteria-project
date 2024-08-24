document.addEventListener('DOMContentLoaded', function () {
    Dropdown();
    displayUsersData();
    loadFilters();
});

function Dropdown() {
    const userDropdown = document.getElementById('users');
    const Names = [...new Set(usersData.map(item => item.name))];
    Names.forEach(name => {
        const option = document.createElement('option');
        option.value = name;
        option.textContent = name;
        userDropdown.appendChild(option);
    });
}

function displayUsersData() {
    const fTableBody = document.getElementById('f_table').getElementsByTagName('tbody')[0];
    fTableBody.innerHTML = '';
    usersData.forEach(user => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${user.name}</td>
            <td>${parseFloat(user.totalAmount).toFixed(2)}</td>
            <td>
                <button class="btn" style="background-color: #5C3C1B;color:white" 
                        onclick="toggleUserOrders(${user.id}, '${user.name}', this)">
                    +
                </button>
            </td>
        `;
        fTableBody.appendChild(row);
    });
}

function toggleUserOrders(userId, userName, button) {
    const sTable = document.getElementById('s_table');
    const sTableBody = sTable.getElementsByTagName('tbody')[0];
    const imagesContainer = document.getElementById('images_container');

    const isExpanded = button.textContent === '-';

    document.querySelectorAll('#f_table button').forEach(btn => {
        if (btn !== button) {
            btn.textContent = '+';
        }
    });
    sTable.style.display = 'none';
    imagesContainer.style.display = 'none';
    imagesContainer.innerHTML = '';

    if (isExpanded) {
        button.textContent = '+';
    } else {
        button.textContent = '-';
        sTable.style.display = 'table';
        sTableBody.innerHTML = '';

        const userOrders = ordersData.filter(order => order.user_id === userId);
        userOrders.forEach(order => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${order.date}</td>
                <td>${parseFloat(order.total).toFixed(2)}</td>
                <td>
                    <button class="btn" style="background-color: #5C3C1B;color:white" 
                            onclick="toggleOrderImages(${order.id}, this)">
                        +
                    </button>
                </td>
            `;
            sTableBody.appendChild(row);
        });
    }
}

function toggleOrderImages(orderId, button) {
    const imagesContainer = document.getElementById('images_container');

    const isExpanded = button.textContent === '-';

    document.querySelectorAll('#s_table button').forEach(btn => {
        if (btn !== button) {
            btn.textContent = '+';
        }
    });
    imagesContainer.style.display = 'none';

    if (isExpanded) {
        button.textContent = '+';
        imagesContainer.style.display = 'none';
        imagesContainer.innerHTML = '';
    } else {
        button.textContent = '-';
        imagesContainer.style.display = 'block';
        imagesContainer.innerHTML = '';

        const orderImages = orderProductsData.filter(product => product.order_id === orderId);
        if (orderImages.length === 0) {
            imagesContainer.innerHTML = '<p class="text-center">No Images Available</p>';
        } else {
            orderImages.forEach(product => {
                const imageElement = `<div class='container mt-3 mb-5'>
                                        <div class='row justify-content-center'>
                                            <div class='col-12 col-md-8 col-lg-6 col-xl-4 '>
                                                <div class='card m-2 flex-shrink-0 h-100 d-flex justify-content-center align-items-center rounded-4 py-0 mt-1'  style='width: 18rem;'>
                                                    <div class='card-body position-relative text-center'>
                                                        <div class='row justify-content-center'>
                                                            <img src='${product.image}' class='img-fluid rounded-4' alt='cup_pic' style='max-width: 90%; background-color:#F8F4E1;'>
                                                            <div class='position-absolute bg-white rounded-circle d-flex align-items-center justify-content-center'
                                                                style='height: 75px; width: 75px; top: -20px; left: -20px;'>
                                                                <h1 style='color:#747d88; font-size: 1.1rem;'>${parseFloat(product.price).toFixed(2)}LE</h1>
                                                            </div>
                                                        </div>
                                                        <div class='d-flex justify-content-between align-items-center mt-3'>
                                                            <h5 class='card-title p-2 fs-5 px-5 rounded-2 text-start' style='color:#5C3C1B; background-color:#AF8F6F;'>${product.name}</h5>
                                                            <p class='card-title p-2 py-1 fs-5  px-3 rounded-2 text-start' style='color:#5C3C1B; background-color:#AF8F6F;'>${product.quantity}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                imagesContainer.innerHTML += imageElement;
            });
        }
    }
}

function filterData() {
    const startDate = document.getElementById('date_from').value;
    const endDate = document.getElementById('date_to').value;
    const selectedUser = document.getElementById('users').value;

    let filteredOrders = ordersData;
    let filteredUsers = usersData;

    if (startDate || endDate) {
        filteredOrders = filteredOrders.filter(item => {
            const itemDate = new Date(item.date).toISOString().split('T')[0];
            if (startDate && endDate) {
                return itemDate >= startDate && itemDate <= endDate;
            } else if (startDate) {
                return itemDate >= startDate;
            } else if (endDate) {
                return itemDate <= endDate;
            }
        });
        const userIdsWithData = new Set(filteredOrders.map(order => order.user_id));
        filteredUsers = filteredUsers.filter(user => userIdsWithData.has(user.id));
    }

    if (selectedUser !== "") {
        const userId = usersData.find(user => user.name === selectedUser)?.id;
        if (userId !== undefined) {
            filteredOrders = filteredOrders.filter(item => item.user_id === userId);
            filteredUsers = filteredUsers.filter(user => user.id === userId);
        }
    }

    displayFilteredData(filteredOrders, filteredUsers);
}

function displayFilteredData(filteredOrders, filteredUsers) {
    const fTableBody = document.getElementById('f_table').getElementsByTagName('tbody')[0];
    const sTableBody = document.getElementById('s_table').getElementsByTagName('tbody')[0];

    fTableBody.innerHTML = '';
    sTableBody.innerHTML = '';

    if (filteredUsers.length === 0 && filteredOrders.length === 0) {
        fTableBody.innerHTML = '<tr><td colspan="3" class="text-center">No Orders Matchs</td></tr>';
        sTableBody.innerHTML = '<tr><td colspan="3" class="text-center">No Orders Matchs</td></tr>';
    } else {
        if (filteredUsers.length === 0) {
            fTableBody.innerHTML = '<tr><td colspan="3" class="text-center">No Orders Matchs</td></tr>';
        } else {
            const userTotals = filteredUsers.reduce((acc, user) => {
                acc[user.id] = 0;
                return acc;
            }, {});

            filteredOrders.forEach(order => {
                if (userTotals[order.user_id] !== undefined) {
                    userTotals[order.user_id] += parseFloat(order.total);
                }
            });

            filteredUsers.forEach(user => {
                const fRow = `<tr>
                                <td>${user.name}</td>
                                <td>${userTotals[user.id].toFixed(2)}</td>
                                <td>
                                    <button class="btn" style="background-color: #5C3C1B;color:white" 
                                            onclick="toggleUserOrders(${user.id}, '${user.name}', this)">
                                        +
                                    </button>
                                </td>
                              </tr>`;
                fTableBody.innerHTML += fRow;
            });
        }

        if (filteredOrders.length === 0) {
            sTableBody.innerHTML = '<tr><td colspan="3" class="text-center">No Orders Matchs </td></tr>';
        } else {
            filteredOrders.forEach(order => {
                const sRow = `<tr>
                                <td>${order.date}</td>
                                <td>${parseFloat(order.total).toFixed(2)}</td>
                                <td>
                                    <button class="btn" style="background-color: #5C3C1B;color:white" 
                                            onclick="toggleOrderImages(${order.id}, this)">
                                        +
                                    </button>
                                </td>
                              </tr>`;
                sTableBody.innerHTML += sRow;
            });
        }
    }
}

function loadFilters() {
    document.getElementById('date_from').value = '';
    document.getElementById('date_to').value = '';
    document.getElementById('users').value = '';
}

document.getElementById('filter_button').addEventListener('click', filterData);
document.getElementById('users').addEventListener('change', filterData);
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
            <td><button class="btn" style="background-color: #5C3C1B;color:white" onclick="showUserOrders(${user.id}, '${user.name}')">+</button></td>
        `;
        fTableBody.appendChild(row);
    });
}

function showUserOrders(userId, userName) {
    const sTable = document.getElementById('s_table');
    const sTableBody = sTable.getElementsByTagName('tbody')[0];
    const imagesContainer = document.getElementById('images_container');

    sTable.style.display = 'table';
    imagesContainer.style.display = 'none';

    sTableBody.innerHTML = '';

    const userOrders = ordersData.filter(order => order.user_id === userId);

    userOrders.forEach(order => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${order.date}</td>
            <td>${parseFloat(order.total).toFixed(2)}</td>
            <td><button class="btn" style="background-color: #5C3C1B;color:white" onclick="showOrderImages(${order.id})">+</button></td>
        `;
        sTableBody.appendChild(row);
    });
}

function showOrderImages(orderId) {
    const imagesContainer = document.getElementById('images_container');
    imagesContainer.style.display = 'block';

    const orderImages = orderProductsData.filter(product => product.order_id === orderId);

    imagesContainer.innerHTML = '';

    orderImages.forEach(product => {
        const imageElement = `
    <div class="card shadow-sm" style="width: 18rem; border-radius: 10px; overflow: hidden;">
        <img src="${product.image}" class="card-img-top" alt="${product.name}" style="height: 200px; object-fit: cover;">
        <div class="card-body text-center">
            <h5 class="card-title mb-2">${product.name}</h5>
            <p class="card-text text-muted mb-1">${parseFloat(product.price).toFixed(2)} EGP</p>
            <span class="badge " style="background-color: #5C3C1B; font-size: 1rem; padding: 0.5rem 1rem;">Quantity: ${product.quantity}</span>
        </div>
    </div>


`;
        imagesContainer.innerHTML += imageElement;
    });
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

    if (selectedUser !== "None") {
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
                                <td><button class="btn" style="background-color: #5C3C1B;color:white" onclick="showUserOrders(${user.id}, '${user.name}')">+</button></td>
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
                                <td><button class="btn btn-primary" onclick="showOrderImages(${order.id})">+</button></td>
                              </tr>`;
                sTableBody.innerHTML += sRow;
            });
        }
    }
}

function loadFilters() {
    document.getElementById('date_from').value = '';
    document.getElementById('date_to').value = '';
    document.getElementById('users').value = 'None';
    filterData();
}

document.getElementById('date_from').addEventListener('input', filterData);
document.getElementById('date_to').addEventListener('input', filterData);
document.getElementById('users').addEventListener('change', filterData);

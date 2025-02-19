let currentUser = null;

document.addEventListener('DOMContentLoaded', function() {
    // Check authentication
    const userStr = localStorage.getItem('user');
    if (!userStr) {
        window.location.href = 'login.html';
        return;
    }
    currentUser = JSON.parse(userStr);
    document.getElementById('userInfo').textContent = `Welcome, ${currentUser.name}`;

    // Logout handler
    document.getElementById('logoutBtn').addEventListener('click', () => {
        localStorage.removeItem('user');
        window.location.href = 'login.html';
    });

    // Initialize data
    loadLeads();
    loadTasks();

    // Setup event listeners
    document.getElementById('saveLead').addEventListener('click', saveLead);
    document.getElementById('saveTask').addEventListener('click', saveTask);

    // Add handler for the "New Task" button
    document.querySelector('[data-bs-target="#taskModal"]').addEventListener('click', function() {
        const form = document.getElementById('taskForm');
        form.reset();
        
        // Show the lead selection dropdown for new standalone tasks
        const leadSelectGroup = form.querySelector('.lead-select-group');
        leadSelectGroup.style.display = 'block';
        
        // Reset modal title
        const modalTitle = document.querySelector('#taskModal .modal-title');
        modalTitle.textContent = 'Task Details';
    });
});

async function loadLeads() {
    try {
        const response = await fetch('api/leads.php');
        const leads = await response.json();
        const tbody = document.querySelector('#leadsTable tbody');
        tbody.innerHTML = '';

        leads.forEach(lead => {
            const tr = document.createElement('tr');
            // Escape the lead object properly for the onclick handlers
            const leadJson = JSON.stringify(lead).replace(/'/g, '&#39;').replace(/"/g, '&quot;');
            tr.innerHTML = `
                <td>${lead.name}</td>
                <td>${lead.email}</td>
                <td>${lead.company}</td>
                <td><span class="badge bg-primary">${lead.status}</span></td>
                <td class="action-buttons">
                    <button class="btn btn-sm btn-primary" onclick='editLead(${leadJson})'>
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-success" onclick='createTaskForLead(${leadJson})'>
                        <i class="bi bi-plus-circle"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteLead('${lead.email}')">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } catch (error) {
        alert('Error loading leads');
    }
}

async function loadTasks() {
    try {
        const response = await fetch('api/tasks.php');
        const tasks = await response.json();
        const tbody = document.querySelector('#tasksTable tbody');
        tbody.innerHTML = '';

        tasks.forEach(task => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${task.title}</td>
                <td>${task.lead_id}</td>
                <td>${task.due_date}</td>
                <td><span class="badge bg-primary">${task.status}</span></td>
                <td><span class="badge bg-${task.priority === 'HIGH' ? 'danger' : 'warning'}">${task.priority}</span></td>
                <td class="action-buttons">
                    <button class="btn btn-sm btn-primary" onclick="editTask(${JSON.stringify(task)})">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteTask('${task.id}')">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } catch (error) {
        alert('Error loading tasks');
    }
}

// Lead CRUD operations
async function saveLead() {
    const form = document.getElementById('leadForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    const isEdit = !!data.id;

    try {
        const response = await fetch('api/leads.php', {
            method: isEdit ? 'PUT' : 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();
        if (result.message) {
            bootstrap.Modal.getInstance(document.getElementById('leadModal')).hide();
            loadLeads();
        } else {
            alert('Operation failed: ' + result.error);
        }
    } catch (error) {
        alert('Operation failed');
    }
}

// Task CRUD operations
async function saveTask() {
    const form = document.getElementById('taskForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    data.created_by = currentUser.id;
    const isEdit = !!data.id;

    try {
        const response = await fetch('api/tasks.php', {
            method: isEdit ? 'PUT' : 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();
        if (result.message) {
            bootstrap.Modal.getInstance(document.getElementById('taskModal')).hide();
            loadTasks();
        } else {
            alert('Operation failed: ' + result.error);
        }
    } catch (error) {
        alert('Operation failed');
    }
}

// Helper functions for edit/delete operations
function editLead(lead) {
    const form = document.getElementById('leadForm');
    Object.keys(lead).forEach(key => {
        const input = form.elements[key];
        if (input) input.value = lead[key];
    });
    const modal = new bootstrap.Modal(document.getElementById('leadModal'));
    modal.show();
}

function createTaskForLead(lead) {
    const form = document.getElementById('taskForm');
    form.reset(); // Reset form first
    
    // Hide the lead selection dropdown group when creating task from lead
    const leadSelectGroup = form.querySelector('.lead-select-group');
    leadSelectGroup.style.display = 'none';
    
    // Set the lead_id
    form.elements['lead_id'].value = lead.id;
    
    // Update modal title to indicate we're creating task for specific lead
    const modalTitle = document.querySelector('#taskModal .modal-title');
    modalTitle.textContent = `New Task for ${lead.name}`;
    
    const modal = new bootstrap.Modal(document.getElementById('taskModal'));
    modal.show();
}

async function deleteLead(email) {
    if (confirm('Are you sure you want to delete this lead?')) {
        try {
            const response = await fetch(`api/leads.php?email=${email}`, {
                method: 'DELETE'
            });
            const result = await response.json();
            if (result.message) {
                loadLeads();
            } else {
                alert('Delete failed: ' + result.error);
            }
        } catch (error) {
            alert('Delete failed');
        }
    }
}

async function deleteTask(id) {
    if (confirm('Are you sure you want to delete this task?')) {
        try {
            const response = await fetch(`api/tasks.php?id=${id}`, {
                method: 'DELETE'
            });
            const result = await response.json();
            if (result.message) {
                loadTasks();
            } else {
                alert('Delete failed: ' + result.error);
            }
        } catch (error) {
            alert('Delete failed');
        }
    }
} 
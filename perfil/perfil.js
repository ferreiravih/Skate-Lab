const buttons = document.querySelectorAll('.menu-item');
const sections = document.querySelectorAll('.section');

buttons.forEach(button => {
  button.addEventListener('click', () => {
    buttons.forEach(btn => btn.classList.remove('active'));
    sections.forEach(sec => sec.classList.remove('active'));

    button.classList.add('active');
    const target = button.getAttribute('data-target');
    document.getElementById(target).classList.add('active');
  });
});


// JS DO PERFIL HEHE

// Variáveis para guardar
let originalData = {};
let currentPhoto = '';

// Inicializar quando a página carregar | preciso arrumar isso slk
document.addEventListener('DOMContentLoaded', function() {
    saveOriginalData();
    currentPhoto = document.getElementById('profile-picture').src;
});


// Salvar dados originais 
function saveOriginalData() {
    const form = document.getElementById('profile-form');
    const inputs = form.querySelectorAll('input');
    
    inputs.forEach(input => {
        originalData[input.name] = input.value;
    });
}

// Restaurar dados originais
function restoreOriginalData() {
    const form = document.getElementById('profile-form');
    const inputs = form.querySelectorAll('input');
    
    inputs.forEach(input => {
        if (originalData[input.name]) {
            input.value = originalData[input.name];
        }
    });
    
    // Restaurar foto original
    document.getElementById('profile-picture').src = currentPhoto;
}

// Função para alternar modo de edição
function toggleEditMode() {
    const form = document.getElementById('profile-form');
    const inputs = form.querySelectorAll('input:not(.readonly-field)');
    const editBtn = document.querySelector('.edit-btn');
    const formActions = document.getElementById('form-actions');
    
    const isEditing = inputs[0].readOnly;
    
    // Alternar estado dos campos
    inputs.forEach(input => {
        input.readOnly = !isEditing;
        input.style.background = isEditing ? '#fff' : '#f8f9fa';
        input.style.cursor = isEditing ? 'text' : 'not-allowed';
        input.style.borderColor = isEditing ? '#6a1b9a' : '#e9ecef';
    });
    
    // Alternar botão
    if (isEditing) {
        editBtn.textContent = 'Cancelar Edição';
        editBtn.style.background = '#6c757d';
        formActions.style.display = 'flex';
        
        // Salvar estado atual antes de editar
        saveOriginalData();
        currentPhoto = document.getElementById('profile-picture').src;
        
    } else {
        editBtn.textContent = 'Editar Perfil';
        editBtn.style.background = '';
        editBtn.style.background = '#6a1b9a';
        formActions.style.display = 'none';
        
        // Restaurar dados originais no cancelar
        restoreOriginalData();
        resetFieldStyles();
    }
}

// Função para upload de foto
function handlePhotoUpload(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    // Validar tipo de arquivo
    if (!file.type.startsWith('image/')) {
        alert('Por favor, selecione uma imagem válida!');
        return;
    }
    
    // Validar tamanho (máximo 5MB)
    if (file.size > 5 * 1024 * 1024) {
        alert('A imagem deve ter no máximo 5MB!');
        return;
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
        const profilePicture = document.getElementById('profile-picture');
        
        // Criar uma nova imagem para garantir o carregamento
        const newImage = new Image();
        newImage.onload = function() {
            // Aplicar a nova foto
            profilePicture.src = e.target.result;
            
            // Atualizar a foto atual
            currentPhoto = e.target.result;
            
            // Feedback visual
            showMessage('Foto atualizada com sucesso!', 'success');
        };
        newImage.src = e.target.result;
    };
    reader.readAsDataURL(file);
}

// Submit do formulário
document.getElementById('profile-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validar dados
    if (!validateForm()) {
        showMessage('Por favor, preencha todos os campos obrigatórios!', 'error');
        return;
    }
    
    // Coletar dados do formulário
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    //Dados p enviar p o servidor! 
    console.log('Dados a serem salvos:', data);
    console.log('Foto atual:', currentPhoto);
    
    // Atualizar dados originais com as novas
    updateOriginalData();
    
    // Feedback
    showMessage('Perfil atualizado com sucesso!', 'success');
    
    // Sair do modo edição
    const editBtn = document.querySelector('.edit-btn');
    const formActions = document.getElementById('form-actions');
    
    editBtn.textContent = 'Editar Perfil';
    editBtn.style.background = '#6a1b9a';
    formActions.style.display = 'none';
    
    // Manter campos como readonly
    const inputs = this.querySelectorAll('input:not(.readonly-field)');
    inputs.forEach(input => {
        input.readOnly = true;
        input.style.background = '#f8f9fa';
        input.style.cursor = 'not-allowed';
        input.style.borderColor = '#e9ecef';
    });
    
    // Atualizar display do username se mudou
    const usernameInput = document.getElementById('username');
    const usernameDisplay = document.getElementById('username-display');
    if (usernameInput.value !== usernameDisplay.textContent) {
        usernameDisplay.textContent = usernameInput.value;
    }
});

// Atualizar dados originais
function updateOriginalData() {
    const form = document.getElementById('profile-form');
    const inputs = form.querySelectorAll('input');
    
    inputs.forEach(input => {
        originalData[input.name] = input.value;
    });
}

// Resetar estilos dos campos
function resetFieldStyles() {
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        input.style.borderColor = '#e9ecef';
        input.style.background = '#f8f9fa';
    });
}

// Função para mostrar mensagens
function showMessage(message, type) {
    // Remover mensagens existentes
    const existingAlerts = document.querySelectorAll('.alert');
    existingAlerts.forEach(alert => alert.remove());
    
    // Criar elemento de mensagem
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert ${type === 'success' ? 'success' : 'error'}`;
    alertDiv.style.cssText = `
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: 500;
        background: ${type === 'success' ? '#d4edda' : '#f8d7da'};
        color: ${type === 'success' ? '#155724' : '#721c24'};
        border: 1px solid ${type === 'success' ? '#c3e6cb' : '#f5c6cb'};
    `;
    alertDiv.textContent = message;
    
    // Inserir antes do container do perfil
    const profileContainer = document.querySelector('.profile-container');
    profileContainer.parentNode.insertBefore(alertDiv, profileContainer);
    
    // Remover após 5 segundos
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Validação do formulário
function validateForm() {
    const requiredFields = document.querySelectorAll('input[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.style.borderColor = '#dc3545';
            isValid = false;
        } else {
            field.style.borderColor = '#28a745';
        }
    });
    
    // Validação específica para email
    const emailField = document.getElementById('email');
    if (emailField.value && !isValidEmail(emailField.value)) {
        emailField.style.borderColor = '#dc3545';
        isValid = false;
    }
    
    return isValid;
}

// Validar email
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Máscara para telefone
document.getElementById('telefone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    
    if (value.length <= 11) {
        if (value.length <= 10) {
            value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
        } else {
            value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        }
        e.target.value = value;
    }
});

// Máscara para CEP
document.getElementById('cep').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    
    if (value.length <= 8) {
        value = value.replace(/(\d{5})(\d)/, '$1-$2');
        e.target.value = value;
    }
    
    // Buscar endereço automaticamente
    if (value.length === 9) {
        // buscarEnderecoPorCEP(value);
    }
});

// Prevenir caracteres não numéricos em campos específicos
document.getElementById('numero').addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, '');
});
<template>
  <Layout>
    <div class="perfil-container">
      <div class="page-header">
        <h1><i class="bi bi-person-circle"></i> Meu Perfil</h1>
      </div>

      <div class="perfil-content">
        <div class="perfil-card">
          <div class="perfil-avatar">
            <div class="avatar-circle">
              <i class="bi bi-person"></i>
            </div>
            <h2>{{ user.name }}</h2>
            <p class="user-email">{{ user.email }}</p>
          </div>

          <div class="perfil-tabs">
            <button
              :class="['tab-btn', { active: activeTab === 'info' }]"
              @click="activeTab = 'info'"
            >
              <i class="bi bi-person"></i> Informações
            </button>
            <button
              :class="['tab-btn', { active: activeTab === 'senha' }]"
              @click="activeTab = 'senha'"
            >
              <i class="bi bi-lock"></i> Alterar Senha
            </button>
          </div>

          <!-- Tab: Informações -->
          <div v-show="activeTab === 'info'" class="tab-content">
            <form @submit.prevent="updateProfile">
              <div class="form-group">
                <label>Nome Completo *</label>
                <input
                  type="text"
                  v-model="profileForm.name"
                  required
                  class="form-control"
                  placeholder="Seu nome completo"
                />
              </div>

              <div class="form-group">
                <label>Email *</label>
                <input
                  type="email"
                  v-model="profileForm.email"
                  required
                  class="form-control"
                  placeholder="seu@email.com"
                />
              </div>

              <div class="form-actions">
                <button type="submit" class="btn-primary" :disabled="savingProfile">
                  <i class="bi bi-check-circle"></i>
                  {{ savingProfile ? 'Salvando...' : 'Salvar Alterações' }}
                </button>
              </div>
            </form>
          </div>

          <!-- Tab: Alterar Senha -->
          <div v-show="activeTab === 'senha'" class="tab-content">
            <form @submit.prevent="updatePassword">
              <div class="form-group">
                <label>Senha Atual *</label>
                <input
                  type="password"
                  v-model="passwordForm.current_password"
                  required
                  class="form-control"
                  placeholder="Digite sua senha atual"
                />
              </div>

              <div class="form-group">
                <label>Nova Senha *</label>
                <input
                  type="password"
                  v-model="passwordForm.new_password"
                  required
                  class="form-control"
                  placeholder="Digite a nova senha"
                  minlength="6"
                />
              </div>

              <div class="form-group">
                <label>Confirmar Nova Senha *</label>
                <input
                  type="password"
                  v-model="passwordForm.new_password_confirmation"
                  required
                  class="form-control"
                  placeholder="Confirme a nova senha"
                  minlength="6"
                />
              </div>

              <div class="form-actions">
                <button type="submit" class="btn-primary" :disabled="savingPassword">
                  <i class="bi bi-lock"></i>
                  {{ savingPassword ? 'Atualizando...' : 'Atualizar Senha' }}
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Informações do Sistema -->
        <div class="system-info">
          <h3><i class="bi bi-info-circle"></i> Informações do Sistema</h3>
          <div class="info-item">
            <span class="label">Versão:</span>
            <span class="value">NailBook v1.0.0</span>
          </div>
          <div class="info-item">
            <span class="label">Último acesso:</span>
            <span class="value">{{ lastAccess }}</span>
          </div>
          <div class="info-item">
            <span class="label">Conta criada em:</span>
            <span class="value">{{ accountCreated }}</span>
          </div>
        </div>
      </div>

      <!-- Mensagens -->
      <div v-if="successMessage" class="alert alert-success">
        <i class="bi bi-check-circle-fill"></i>
        {{ successMessage }}
      </div>

      <div v-if="errorMessage" class="alert alert-danger">
        <i class="bi bi-exclamation-triangle-fill"></i>
        {{ errorMessage }}
      </div>
    </div>
  </Layout>
</template>

<script>
import axios from 'axios';
import Layout from './Layout.vue';

export default {
  name: 'Perfil',
  components: {
    Layout
  },
  data() {
    return {
      activeTab: 'info',
      user: {},
      profileForm: {
        name: '',
        email: ''
      },
      passwordForm: {
        current_password: '',
        new_password: '',
        new_password_confirmation: ''
      },
      savingProfile: false,
      savingPassword: false,
      successMessage: '',
      errorMessage: ''
    };
  },
  computed: {
    lastAccess() {
      return new Date().toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    },
    accountCreated() {
      if (this.user.created_at) {
        return new Date(this.user.created_at).toLocaleDateString('pt-BR', {
          day: '2-digit',
          month: 'long',
          year: 'numeric'
        });
      }
      return '-';
    }
  },
  mounted() {
    this.loadUserData();
  },
  methods: {
    loadUserData() {
      const user = JSON.parse(localStorage.getItem('user') || '{}');
      this.user = user;
      this.profileForm.name = user.name || '';
      this.profileForm.email = user.email || '';
    },

    async updateProfile() {
      this.savingProfile = true;
      this.errorMessage = '';
      this.successMessage = '';

      try {
        const response = await axios.put('/api/user/profile', this.profileForm);

        // Atualizar localStorage
        const updatedUser = { ...this.user, ...this.profileForm };
        localStorage.setItem('user', JSON.stringify(updatedUser));
        this.user = updatedUser;

        this.successMessage = 'Perfil atualizado com sucesso!';

        setTimeout(() => {
          this.successMessage = '';
        }, 3000);
      } catch (error) {
        console.error('Erro ao atualizar perfil:', error);
        this.errorMessage = error.response?.data?.message || 'Erro ao atualizar perfil';

        setTimeout(() => {
          this.errorMessage = '';
        }, 3000);
      } finally {
        this.savingProfile = false;
      }
    },

    async updatePassword() {
      if (this.passwordForm.new_password !== this.passwordForm.new_password_confirmation) {
        this.errorMessage = 'As senhas não coincidem';
        setTimeout(() => {
          this.errorMessage = '';
        }, 3000);
        return;
      }

      this.savingPassword = true;
      this.errorMessage = '';
      this.successMessage = '';

      try {
        await axios.put('/api/user/password', this.passwordForm);

        this.successMessage = 'Senha atualizada com sucesso!';

        // Limpar formulário
        this.passwordForm = {
          current_password: '',
          new_password: '',
          new_password_confirmation: ''
        };

        setTimeout(() => {
          this.successMessage = '';
        }, 3000);
      } catch (error) {
        console.error('Erro ao atualizar senha:', error);
        this.errorMessage = error.response?.data?.message || 'Erro ao atualizar senha';

        setTimeout(() => {
          this.errorMessage = '';
        }, 3000);
      } finally {
        this.savingPassword = false;
      }
    }
  }
};
</script>

<style scoped>
.perfil-container {
  padding: 0;
  background: #f5f7fa;
  min-height: 100vh;
}

.page-header {
  background: white;
  padding: 30px 40px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  margin-bottom: 30px;
}

.page-header h1 {
  margin: 0;
  color: #55165e;
  font-size: 28px;
  display: flex;
  align-items: center;
  gap: 12px;
}

.perfil-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 40px 40px;
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 30px;
}

.perfil-card {
  background: white;
  border-radius: 12px;
  padding: 40px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.perfil-avatar {
  text-align: center;
  padding-bottom: 30px;
  border-bottom: 2px solid #f0f0f0;
  margin-bottom: 30px;
}

.avatar-circle {
  width: 120px;
  height: 120px;
  margin: 0 auto 20px;
  background: linear-gradient(135deg, #55165e, #7a2087);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 8px 20px rgba(85, 22, 94, 0.3);
}

.avatar-circle i {
  font-size: 60px;
  color: white;
}

.perfil-avatar h2 {
  margin: 0 0 8px 0;
  color: #333;
  font-size: 24px;
}

.user-email {
  color: #666;
  font-size: 16px;
  margin: 0;
}

.perfil-tabs {
  display: flex;
  gap: 10px;
  margin-bottom: 30px;
  border-bottom: 2px solid #f0f0f0;
}

.tab-btn {
  flex: 1;
  padding: 15px;
  background: none;
  border: none;
  border-bottom: 3px solid transparent;
  color: #666;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.tab-btn:hover {
  color: #55165e;
  background: #f8f9fa;
}

.tab-btn.active {
  color: #55165e;
  border-bottom-color: #55165e;
}

.tab-content {
  animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.form-group {
  margin-bottom: 25px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  color: #333;
  font-weight: 500;
  font-size: 14px;
}

.form-control {
  width: 100%;
  padding: 12px 15px;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  font-size: 15px;
  transition: all 0.3s ease;
}

.form-control:focus {
  outline: none;
  border-color: #55165e;
  box-shadow: 0 0 0 3px rgba(85, 22, 94, 0.1);
}

.form-actions {
  margin-top: 30px;
}

.btn-primary {
  padding: 12px 30px;
  background: linear-gradient(135deg, #55165e, #7a2087);
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.btn-primary:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(85, 22, 94, 0.3);
}

.btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* Informações do Sistema */
.system-info {
  background: white;
  border-radius: 12px;
  padding: 30px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  height: fit-content;
}

.system-info h3 {
  margin: 0 0 20px 0;
  color: #55165e;
  font-size: 18px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.info-item {
  display: flex;
  justify-content: space-between;
  padding: 12px 0;
  border-bottom: 1px solid #f0f0f0;
}

.info-item:last-child {
  border-bottom: none;
}

.info-item .label {
  color: #666;
  font-size: 14px;
}

.info-item .value {
  color: #333;
  font-weight: 500;
  font-size: 14px;
}

/* Alertas */
.alert {
  position: fixed;
  top: 20px;
  right: 20px;
  padding: 15px 20px;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  z-index: 2000;
  animation: slideIn 0.3s ease;
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 500;
}

.alert-success {
  background: #d4edda;
  color: #155724;
  border: 2px solid #c3e6cb;
}

.alert-danger {
  background: #f8d7da;
  color: #721c24;
  border: 2px solid #f5c6cb;
}

@keyframes slideIn {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

/* Responsivo */
@media (max-width: 968px) {
  .perfil-content {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .perfil-content {
    padding: 0 20px 20px;
  }

  .perfil-card {
    padding: 20px;
  }

  .page-header {
    padding: 20px;
  }
}
</style>

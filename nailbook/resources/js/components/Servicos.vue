<template>
  <Layout>
    <div class="servicos-container">
      <div class="page-header">
        <h1><i class="bi bi-scissors"></i> Serviços</h1>
        <button @click="showModal = true" class="btn-primary">
          <i class="bi bi-plus-circle"></i> Novo Serviço
        </button>
      </div>

    <div class="servicos-list">
      <div v-if="loading" class="loading">Carregando...</div>

      <div v-else-if="servicos.length === 0" class="empty-state">
        <i class="bi bi-inbox"></i>
        <p>Nenhum serviço cadastrado ainda.</p>
        <button @click="showModal = true" class="btn-primary">Cadastrar Primeiro Serviço</button>
      </div>

      <div v-else class="services-grid">
        <div v-for="servico in servicos" :key="servico.id" class="service-card">
          <div class="service-header">
            <h3>{{ servico.nome }}</h3>
            <div class="service-actions">
              <button @click="editServico(servico)" class="btn-edit" title="Editar">
                <i class="bi bi-pencil"></i>
              </button>
              <button @click="deleteServico(servico)" class="btn-delete" title="Excluir">
                <i class="bi bi-trash"></i>
              </button>
            </div>
          </div>
          <div class="service-info">
            <div class="info-item">
              <i class="bi bi-currency-dollar"></i>
              <span>R$ {{ Number(servico.preco).toFixed(2) }}</span>
            </div>
            <div class="info-item">
              <i class="bi bi-clock"></i>
              <span>{{ servico.duracao }} min</span>
            </div>
          </div>
          <p v-if="servico.descricao" class="service-description">
            {{ servico.descricao }}
          </p>
        </div>
      </div>
    </div>

    <!-- Modal de Cadastro/Edição -->
    <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
      <div class="modal-content">
        <div class="modal-header">
          <h2>{{ editingServico ? 'Editar Serviço' : 'Novo Serviço' }}</h2>
          <button @click="closeModal" class="btn-close">
            <i class="bi bi-x"></i>
          </button>
        </div>

        <form @submit.prevent="saveServico">
          <div class="form-group">
            <label>Nome do Serviço *</label>
            <input
              type="text"
              v-model="form.nome"
              required
              class="form-control"
              placeholder="Ex: Manicure, Pedicure, Esmaltação..."
            />
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Preço (R$) *</label>
              <input
                type="number"
                step="0.01"
                min="0"
                v-model="form.preco"
                required
                class="form-control"
                placeholder="0,00"
              />
            </div>

            <div class="form-group">
              <label>Duração (minutos) *</label>
              <input
                type="number"
                min="1"
                v-model="form.duracao"
                required
                class="form-control"
                placeholder="30"
              />
            </div>
          </div>

          <div class="form-group">
            <label>Descrição</label>
            <textarea
              v-model="form.descricao"
              class="form-control"
              rows="3"
              placeholder="Descrição do serviço..."
            ></textarea>
          </div>

          <div class="modal-actions">
            <button type="button" @click="closeModal" class="btn-secondary">
              Cancelar
            </button>
            <button type="submit" class="btn-primary" :disabled="saving">
              {{ saving ? 'Salvando...' : 'Salvar' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal de Exclusão -->
    <div v-if="showDeleteModal" class="modal-overlay" @click.self="closeDeleteModal">
      <div class="modal-delete">
        <div class="modal-delete-icon">
          <i class="bi bi-exclamation-triangle"></i>
        </div>

        <h2>Confirmar Exclusão</h2>

        <p>Tem certeza que deseja excluir o serviço</p>
        <p class="servico-name">"{{ servicoToDelete?.nome }}"?</p>

        <p class="warning-text">
          <i class="bi bi-info-circle"></i>
          Esta ação não pode ser desfeita.
        </p>

        <div class="modal-delete-actions">
          <button
            type="button"
            @click="closeDeleteModal"
            class="btn-cancel"
            :disabled="deleting"
          >
            Cancelar
          </button>
          <button
            type="button"
            @click="confirmDelete"
            class="btn-delete-confirm"
            :disabled="deleting"
          >
            <i class="bi bi-trash"></i>
            {{ deleting ? 'Excluindo...' : 'Sim, Excluir' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Mensagem de sucesso -->
    <div v-if="successMessage" class="alert alert-success">
      {{ successMessage }}
    </div>

    <!-- Mensagem de erro -->
    <div v-if="errorMessage" class="alert alert-danger">
      {{ errorMessage }}
    </div>
    </div>
  </Layout>
</template>

<script>
import axios from 'axios';
import Layout from './Layout.vue';

export default {
  name: 'Servicos',
  components: {
    Layout
  },
  data() {
    return {
      servicos: [],
      loading: false,
      showModal: false,
      showDeleteModal: false,
      servicoToDelete: null,
      editingServico: null,
      saving: false,
      deleting: false,
      successMessage: '',
      errorMessage: '',
      form: {
        nome: '',
        preco: '',
        duracao: '',
        descricao: ''
      }
    };
  },
  mounted() {
    this.loadServicos();
  },
  methods: {
    async loadServicos() {
      this.loading = true;
      try {
        const response = await axios.get('/api/servicos');
        this.servicos = response.data;
      } catch (error) {
        this.showError('Erro ao carregar serviços');
        console.error(error);
      } finally {
        this.loading = false;
      }
    },

    editServico(servico) {
      this.editingServico = servico;
      this.form = {
        nome: servico.nome,
        preco: servico.preco,
        duracao: servico.duracao,
        descricao: servico.descricao || ''
      };
      this.showModal = true;
    },

    async saveServico() {
      this.saving = true;
      try {
        if (this.editingServico) {
          await axios.put(`/api/servicos/${this.editingServico.id}`, this.form);
          this.showSuccess('Serviço atualizado com sucesso!');
        } else {
          await axios.post('/api/servicos', this.form);
          this.showSuccess('Serviço cadastrado com sucesso!');
        }

        this.closeModal();
        this.loadServicos();
      } catch (error) {
        this.showError('Erro ao salvar serviço');
        console.error(error);
      } finally {
        this.saving = false;
      }
    },

    async deleteServico(servico) {
      this.servicoToDelete = servico;
      this.showDeleteModal = true;
    },

    async confirmDelete() {
      if (!this.servicoToDelete) return;

      this.deleting = true;
      try {
        await axios.delete(`/api/servicos/${this.servicoToDelete.id}`);
        this.showSuccess('Serviço removido com sucesso!');
        this.loadServicos();
        this.closeDeleteModal();
      } catch (error) {
        this.showError('Erro ao remover serviço');
        console.error(error);
      } finally {
        this.deleting = false;
      }
    },

    closeDeleteModal() {
      this.showDeleteModal = false;
      this.servicoToDelete = null;
    },

    closeModal() {
      this.showModal = false;
      this.editingServico = null;
      this.form = {
        nome: '',
        preco: '',
        duracao: '',
        descricao: ''
      };
    },

    showSuccess(message) {
      this.successMessage = message;
      setTimeout(() => {
        this.successMessage = '';
      }, 3000);
    },

    showError(message) {
      this.errorMessage = message;
      setTimeout(() => {
        this.errorMessage = '';
      }, 3000);
    }
  }
};
</script>

<style scoped>
.servicos-container {
  min-height: 100vh;
  padding: 20px;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  background: white;
  padding: 20px 30px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.page-header h1 {
  color: #55165e;
  margin: 0;
  font-size: 28px;
}

.page-header h1 i {
  margin-right: 10px;
}

.servicos-list {
  background: white;
  border-radius: 10px;
  padding: 30px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.loading {
  text-align: center;
  padding: 40px;
  color: #666;
}

.empty-state {
  text-align: center;
  padding: 60px 20px;
}

.empty-state i {
  font-size: 64px;
  color: #ccc;
  margin-bottom: 20px;
}

.empty-state p {
  color: #666;
  margin-bottom: 20px;
}

.services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
}

.service-card {
  background: #f8f9fa;
  border-radius: 10px;
  padding: 20px;
  border: 2px solid transparent;
  transition: all 0.3s;
}

.service-card:hover {
  border-color: #55165e;
  transform: translateY(-5px);
  box-shadow: 0 5px 15px rgba(85, 22, 94, 0.2);
}

.service-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 15px;
}

.service-header h3 {
  color: #55165e;
  margin: 0;
  font-size: 20px;
  flex: 1;
}

.service-actions {
  display: flex;
  gap: 5px;
}

.service-info {
  display: flex;
  gap: 20px;
  margin-bottom: 10px;
}

.info-item {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #666;
  font-size: 16px;
  font-weight: 500;
}

.info-item i {
  color: #55165e;
  font-size: 18px;
}

.service-description {
  color: #666;
  font-size: 14px;
  margin: 10px 0 0 0;
  line-height: 1.5;
}

.btn-primary {
  background: #55165e;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s;
  font-size: 14px;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.btn-primary:hover:not(:disabled) {
  background: #7a1f85;
  transform: translateY(-2px);
}

.btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-secondary {
  background: #6c757d;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-secondary:hover {
  background: #5a6268;
}

.btn-edit {
  background: #ffc107;
  color: #000;
  border: none;
  padding: 8px 12px;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-edit:hover {
  background: #e0a800;
}

.btn-delete {
  background: #dc3545;
  color: white;
  border: none;
  padding: 8px 12px;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-delete:hover {
  background: #c82333;
}

/* Modal */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  border-radius: 10px;
  width: 90%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 30px;
  border-bottom: 1px solid #eee;
}

.modal-header h2 {
  color: #55165e;
  margin: 0;
}

.btn-close {
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
  color: #666;
  padding: 0;
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.btn-close:hover {
  color: #55165e;
}

form {
  padding: 30px;
}

.form-group {
  margin-bottom: 20px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  color: #333;
  font-weight: 500;
}

.form-control {
  width: 100%;
  padding: 10px 15px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 14px;
  transition: border-color 0.3s;
}

.form-control:focus {
  outline: none;
  border-color: #55165e;
}

textarea.form-control {
  resize: vertical;
  font-family: inherit;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 30px;
}

/* Alertas */
.alert {
  position: fixed;
  top: 20px;
  right: 20px;
  padding: 15px 20px;
  border-radius: 5px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  z-index: 2000;
  animation: slideIn 0.3s ease;
}

.alert-success {
  background: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.alert-danger {
  background: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
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

/* Modal de Exclusão */
.modal-delete {
  background: white;
  border-radius: 16px;
  padding: 40px;
  max-width: 450px;
  width: 90%;
  text-align: center;
  animation: modalSlideIn 0.3s ease;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.modal-delete-icon {
  width: 80px;
  height: 80px;
  margin: 0 auto 20px;
  background: linear-gradient(135deg, #ff6b6b, #dc3545);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  animation: pulse 2s infinite;
}

.modal-delete-icon i {
  font-size: 40px;
  color: white;
}

@keyframes pulse {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
}

.modal-delete h2 {
  color: #333;
  margin-bottom: 15px;
  font-size: 24px;
  font-weight: 600;
}

.modal-delete p {
  color: #666;
  margin-bottom: 10px;
  font-size: 16px;
}

.servico-name {
  color: #55165e;
  font-weight: 700;
  font-size: 18px;
  margin: 15px 0;
}

.warning-text {
  background: #fff3cd;
  color: #856404;
  padding: 12px 15px;
  border-radius: 8px;
  margin: 20px 0;
  font-size: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.warning-text i {
  font-size: 18px;
}

.modal-delete-actions {
  display: flex;
  gap: 12px;
  margin-top: 30px;
}

.btn-cancel {
  flex: 1;
  padding: 12px 24px;
  background: #6c757d;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 16px;
  font-weight: 500;
  transition: all 0.3s ease;
}

.btn-cancel:hover:not(:disabled) {
  background: #5a6268;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
}

.btn-delete-confirm {
  flex: 1;
  padding: 12px 24px;
  background: linear-gradient(135deg, #dc3545, #c82333);
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 16px;
  font-weight: 600;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.btn-delete-confirm:hover:not(:disabled) {
  background: linear-gradient(135deg, #c82333, #bd2130);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
}

.btn-cancel:disabled,
.btn-delete-confirm:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
}

@keyframes modalSlideIn {
  from {
    transform: translateY(-50px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}
</style>

<template>
  <Layout>
    <div class="clientes-container">
      <div class="page-header">
        <h1><i class="bi bi-people"></i> Clientes</h1>
        <button @click="showModal = true" class="btn-primary">
          <i class="bi bi-plus-circle"></i> Novo Cliente
        </button>
      </div>

    <div class="clientes-list">
      <div v-if="loading" class="loading">Carregando...</div>

      <div v-else-if="clientes.length === 0" class="empty-state">
        <i class="bi bi-inbox"></i>
        <p>Nenhum cliente cadastrado ainda.</p>
        <button @click="showModal = true" class="btn-primary">Cadastrar Primeiro Cliente</button>
      </div>

      <div v-else class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Nome</th>
              <th>Telefone</th>
              <th>Email</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="cliente in clientes" :key="cliente.id">
              <td>{{ cliente.nome }}</td>
              <td>{{ cliente.telefone }}</td>
              <td>{{ cliente.email || '-' }}</td>
              <td>
                <button @click="editCliente(cliente)" class="btn-edit" title="Editar">
                  <i class="bi bi-pencil"></i>
                </button>
                <button @click="deleteCliente(cliente)" class="btn-delete" title="Excluir">
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal de Cadastro/Edição -->
    <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
      <div class="modal-content">
        <div class="modal-header">
          <h2>{{ editingCliente ? 'Editar Cliente' : 'Novo Cliente' }}</h2>
          <button @click="closeModal" class="btn-close">
            <i class="bi bi-x"></i>
          </button>
        </div>

        <form @submit.prevent="saveCliente">
          <div class="form-group">
            <label>Nome *</label>
            <input
              type="text"
              v-model="form.nome"
              required
              class="form-control"
              placeholder="Nome completo"
            />
          </div>

          <div class="form-group">
            <label>Telefone *</label>
            <input
              type="tel"
              v-model="form.telefone"
              required
              class="form-control"
              placeholder="(00) 00000-0000"
              @input="formatPhoneNumber"
              maxlength="15"
            />
          </div>

          <div class="form-group">
            <label>Email</label>
            <input
              type="email"
              v-model="form.email"
              class="form-control"
              placeholder="email@exemplo.com"
            />
          </div>

          <div class="form-group">
            <label>Observações</label>
            <textarea
              v-model="form.observacoes"
              class="form-control"
              rows="3"
              placeholder="Observações sobre o cliente..."
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
  name: 'Clientes',
  components: {
    Layout
  },
  data() {
    return {
      clientes: [],
      loading: false,
      showModal: false,
      editingCliente: null,
      saving: false,
      successMessage: '',
      errorMessage: '',
      form: {
        nome: '',
        telefone: '',
        email: '',
        observacoes: ''
      }
    };
  },
  mounted() {
    this.loadClientes();
  },
  methods: {
    async loadClientes() {
      this.loading = true;
      try {
        const response = await axios.get('/api/clientes');
        this.clientes = response.data;
      } catch (error) {
        this.showError('Erro ao carregar clientes');
        console.error(error);
      } finally {
        this.loading = false;
      }
    },

    editCliente(cliente) {
      this.editingCliente = cliente;
      this.form = {
        nome: cliente.nome,
        telefone: cliente.telefone,
        email: cliente.email || '',
        observacoes: cliente.observacoes || ''
      };
      this.showModal = true;
    },

    async saveCliente() {
      this.saving = true;
      try {
        if (this.editingCliente) {
          await axios.put(`/api/clientes/${this.editingCliente.id}`, this.form);
          this.showSuccess('Cliente atualizado com sucesso!');
        } else {
          await axios.post('/api/clientes', this.form);
          this.showSuccess('Cliente cadastrado com sucesso!');
        }

        this.closeModal();
        this.loadClientes();
      } catch (error) {
        this.showError('Erro ao salvar cliente');
        console.error(error);
      } finally {
        this.saving = false;
      }
    },

    async deleteCliente(cliente) {
      if (!confirm(`Tem certeza que deseja excluir ${cliente.nome}?`)) {
        return;
      }

      try {
        await axios.delete(`/api/clientes/${cliente.id}`);
        this.showSuccess('Cliente removido com sucesso!');
        this.loadClientes();
      } catch (error) {
        this.showError('Erro ao remover cliente');
        console.error(error);
      }
    },

    closeModal() {
      this.showModal = false;
      this.editingCliente = null;
      this.form = {
        nome: '',
        telefone: '',
        email: '',
        observacoes: ''
      };
    },

    formatPhoneNumber(event) {
      let input = event.target.value.replace(/\D/g, '');

      if (input.length <= 10) {
        input = input.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
      } else {
        input = input.replace(/^(\d{2})(\d{5})(\d{0,4}).*/, '($1) $2-$3');
      }

      this.form.telefone = input;
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
.clientes-container {
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

.clientes-list {
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

.table-responsive {
  overflow-x: auto;
}

.table {
  width: 100%;
  border-collapse: collapse;
}

.table thead {
  background: #55165e;
  color: white;
}

.table th,
.table td {
  padding: 15px;
  text-align: left;
  border-bottom: 1px solid #eee;
}

.table tbody tr:hover {
  background: #f8f9fa;
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
  margin-right: 5px;
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
</style>

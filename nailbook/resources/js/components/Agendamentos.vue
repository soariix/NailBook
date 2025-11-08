<template>
  <Layout>
    <div class="agendamentos-container">
      <div class="page-header">
        <h1><i class="bi bi-calendar-check"></i> Agendamentos</h1>
        <router-link to="/novo-agendamento" class="btn-primary">
          <i class="bi bi-plus-circle"></i> Novo Agendamento
        </router-link>
      </div>

      <!-- Filtros -->
      <div class="filtros-container">
        <div class="filtros-card">
          <div class="filtro-group">
            <label><i class="bi bi-calendar3"></i> Período:</label>
            <select v-model="filtro.periodo" @change="aplicarFiltro" class="form-control">
              <option value="hoje">Hoje</option>
              <option value="semana">Esta Semana</option>
              <option value="mes">Este Mês</option>
              <option value="proximo-mes">Próximo Mês</option>
              <option value="todos">Todos</option>
              <option value="personalizado">Personalizado</option>
            </select>
          </div>

          <div v-if="filtro.periodo === 'personalizado'" class="filtro-group">
            <label>Data Inicial:</label>
            <input type="date" v-model="filtro.dataInicio" @change="aplicarFiltro" class="form-control" />
          </div>

          <div v-if="filtro.periodo === 'personalizado'" class="filtro-group">
            <label>Data Final:</label>
            <input type="date" v-model="filtro.dataFim" @change="aplicarFiltro" class="form-control" />
          </div>

          <div class="filtro-group">
            <label><i class="bi bi-flag"></i> Status:</label>
            <select v-model="filtro.status" @change="aplicarFiltro" class="form-control">
              <option value="">Todos</option>
              <option value="pendente">Pendente</option>
              <option value="confirmado">Confirmado</option>
              <option value="concluido">Concluído</option>
              <option value="cancelado">Cancelado</option>
            </select>
          </div>

          <div class="filtro-group">
            <label><i class="bi bi-search"></i> Buscar Cliente:</label>
            <input
              type="text"
              v-model="filtro.busca"
              @input="aplicarFiltro"
              placeholder="Nome ou telefone..."
              class="form-control"
            />
          </div>
        </div>

        <div class="total-agendamentos">
          <i class="bi bi-calendar-event"></i>
          <strong>{{ agendamentosFiltrados.length }}</strong> agendamento(s) encontrado(s)
        </div>
      </div>

      <!-- Lista de Agendamentos -->
      <div class="agendamentos-content">
        <div v-if="loading" class="loading">
          <i class="bi bi-hourglass-split"></i> Carregando agendamentos...
        </div>

        <div v-else-if="agendamentosFiltrados.length === 0" class="empty-state">
          <i class="bi bi-calendar-x"></i>
          <p>Nenhum agendamento encontrado para os filtros selecionados.</p>
        </div>

        <div v-else class="agendamentos-list">
          <!-- Agrupar por data -->
          <div v-for="(grupo, data) in agendamentosAgrupados" :key="data" class="data-group">
            <div class="data-header">
              <i class="bi bi-calendar-date"></i>
              <h2>{{ formatarDataGrupo(data) }}</h2>
              <span class="count-badge">{{ grupo.length }}</span>
            </div>

            <div class="agendamentos-grid">
              <div
                v-for="agendamento in grupo"
                :key="agendamento.id"
                class="agendamento-card"
                :class="[`status-${agendamento.status}`]"
              >
                <div class="card-header">
                  <div class="cliente-info">
                    <h3>{{ agendamento.cliente_nome }}</h3>
                    <p class="telefone">
                      <i class="bi bi-telephone"></i>
                      {{ agendamento.cliente_telefone }}
                    </p>
                  </div>
                  <span :class="`status-badge status-${agendamento.status}`">
                    {{ getStatusLabel(agendamento.status) }}
                  </span>
                </div>

                <div class="card-body">
                  <div class="info-row">
                    <i class="bi bi-clock"></i>
                    <strong>{{ agendamento.hora }}</strong>
                  </div>
                  <div class="info-row">
                    <i class="bi bi-scissors"></i>
                    <span>{{ agendamento.servico }}</span>
                  </div>
                  <div class="info-row">
                    <i class="bi bi-currency-dollar"></i>
                    <strong>R$ {{ parseFloat(agendamento.valor).toFixed(2).replace('.', ',') }}</strong>
                  </div>
                  <div v-if="agendamento.observacoes" class="info-row observacoes">
                    <i class="bi bi-chat-text"></i>
                    <span>{{ agendamento.observacoes }}</span>
                  </div>
                </div>

                <div class="card-actions">
                  <button
                    v-if="agendamento.status === 'pendente'"
                    @click="confirmarAgendamento(agendamento)"
                    class="btn-action btn-confirmar"
                    title="Confirmar"
                  >
                    <i class="bi bi-check-circle"></i>
                    Confirmar
                  </button>
                  <button
                    v-if="agendamento.status === 'confirmado'"
                    @click="concluirAgendamento(agendamento)"
                    class="btn-action btn-concluir"
                    title="Concluir"
                  >
                    <i class="bi bi-check-all"></i>
                    Concluir
                  </button>
                  <button
                    v-if="agendamento.status !== 'cancelado' && agendamento.status !== 'concluido'"
                    @click="abrirModalCancelar(agendamento)"
                    class="btn-action btn-cancelar"
                    title="Cancelar"
                  >
                    <i class="bi bi-x-circle"></i>
                    Cancelar
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal de Cancelamento -->
      <div v-if="showCancelModal" class="modal-overlay" @click.self="fecharModalCancelar">
        <div class="modal-delete">
          <div class="modal-delete-icon">
            <i class="bi bi-x-circle"></i>
          </div>

          <h2>Cancelar Agendamento</h2>

          <p>Tem certeza que deseja cancelar o agendamento de</p>
          <p class="cliente-name">{{ agendamentoParaCancelar?.cliente_nome }}?</p>

          <div class="agendamento-details">
            <p><strong>Data:</strong> {{ formatarData(agendamentoParaCancelar?.data) }}</p>
            <p><strong>Hora:</strong> {{ agendamentoParaCancelar?.hora }}</p>
            <p><strong>Serviço:</strong> {{ agendamentoParaCancelar?.servico }}</p>
          </div>

          <div class="modal-delete-actions">
            <button
              type="button"
              @click="fecharModalCancelar"
              class="btn-cancel"
              :disabled="cancelando"
            >
              Voltar
            </button>
            <button
              type="button"
              @click="confirmarCancelamento"
              class="btn-delete-confirm"
              :disabled="cancelando"
            >
              <i class="bi bi-x-circle"></i>
              {{ cancelando ? 'Cancelando...' : 'Sim, Cancelar' }}
            </button>
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
  name: 'Agendamentos',
  components: {
    Layout
  },
  data() {
    return {
      agendamentos: [],
      loading: false,
      filtro: {
        periodo: 'mes',
        dataInicio: '',
        dataFim: '',
        status: '',
        busca: ''
      },
      showCancelModal: false,
      agendamentoParaCancelar: null,
      cancelando: false,
      successMessage: '',
      errorMessage: ''
    };
  },
  computed: {
    agendamentosFiltrados() {
      let filtrados = [...this.agendamentos];

      // Filtro por período
      const hoje = new Date();
      hoje.setHours(0, 0, 0, 0);

      if (this.filtro.periodo === 'hoje') {
        const hojeStr = hoje.toISOString().split('T')[0];
        filtrados = filtrados.filter(a => a.data === hojeStr);
      } else if (this.filtro.periodo === 'semana') {
        const fimSemana = new Date(hoje);
        fimSemana.setDate(hoje.getDate() + 7);
        filtrados = filtrados.filter(a => {
          const dataAgend = new Date(a.data);
          return dataAgend >= hoje && dataAgend <= fimSemana;
        });
      } else if (this.filtro.periodo === 'mes') {
        const mesAtual = hoje.getMonth();
        const anoAtual = hoje.getFullYear();
        filtrados = filtrados.filter(a => {
          const dataAgend = new Date(a.data);
          return dataAgend.getMonth() === mesAtual && dataAgend.getFullYear() === anoAtual;
        });
      } else if (this.filtro.periodo === 'proximo-mes') {
        const proximoMes = new Date(hoje);
        proximoMes.setMonth(hoje.getMonth() + 1);
        const mes = proximoMes.getMonth();
        const ano = proximoMes.getFullYear();
        filtrados = filtrados.filter(a => {
          const dataAgend = new Date(a.data);
          return dataAgend.getMonth() === mes && dataAgend.getFullYear() === ano;
        });
      } else if (this.filtro.periodo === 'personalizado') {
        if (this.filtro.dataInicio) {
          filtrados = filtrados.filter(a => a.data >= this.filtro.dataInicio);
        }
        if (this.filtro.dataFim) {
          filtrados = filtrados.filter(a => a.data <= this.filtro.dataFim);
        }
      }

      // Filtro por status
      if (this.filtro.status) {
        filtrados = filtrados.filter(a => a.status === this.filtro.status);
      }

      // Filtro por busca (nome ou telefone)
      if (this.filtro.busca) {
        const busca = this.filtro.busca.toLowerCase();
        filtrados = filtrados.filter(a =>
          a.cliente_nome.toLowerCase().includes(busca) ||
          a.cliente_telefone.includes(busca)
        );
      }

      return filtrados;
    },

    agendamentosAgrupados() {
      const grupos = {};

      this.agendamentosFiltrados.forEach(agendamento => {
        if (!grupos[agendamento.data]) {
          grupos[agendamento.data] = [];
        }
        grupos[agendamento.data].push(agendamento);
      });

      // Ordenar por data
      const ordenado = {};
      Object.keys(grupos).sort().forEach(data => {
        // Ordenar por hora dentro de cada data
        ordenado[data] = grupos[data].sort((a, b) => a.hora.localeCompare(b.hora));
      });

      return ordenado;
    }
  },
  mounted() {
    this.carregarAgendamentos();
  },
  methods: {
    async carregarAgendamentos() {
      this.loading = true;
      try {
        const response = await axios.get('/api/agendamentos');
        this.agendamentos = response.data;
      } catch (error) {
        console.error('Erro ao carregar agendamentos:', error);

        if (error.response && error.response.status === 401) {
          localStorage.removeItem('auth_token');
          this.$router.push('/');
        } else {
          this.showError('Erro ao carregar agendamentos');
        }
      } finally {
        this.loading = false;
      }
    },

    aplicarFiltro() {
      // Força recálculo dos computeds
    },

    async confirmarAgendamento(agendamento) {
      try {
        await axios.put(`/api/agendamentos/${agendamento.id}`, {
          ...agendamento,
          status: 'confirmado'
        });

        this.showSuccess('Agendamento confirmado!');
        this.carregarAgendamentos();
      } catch (error) {
        console.error('Erro ao confirmar:', error);
        this.showError('Erro ao confirmar agendamento');
      }
    },

    async concluirAgendamento(agendamento) {
      try {
        await axios.put(`/api/agendamentos/${agendamento.id}`, {
          ...agendamento,
          status: 'concluido'
        });

        this.showSuccess('Agendamento concluído!');
        this.carregarAgendamentos();
      } catch (error) {
        console.error('Erro ao concluir:', error);
        this.showError('Erro ao concluir agendamento');
      }
    },

    abrirModalCancelar(agendamento) {
      this.agendamentoParaCancelar = agendamento;
      this.showCancelModal = true;
    },

    fecharModalCancelar() {
      this.showCancelModal = false;
      this.agendamentoParaCancelar = null;
    },

    async confirmarCancelamento() {
      if (!this.agendamentoParaCancelar) return;

      this.cancelando = true;
      try {
        await axios.put(`/api/agendamentos/${this.agendamentoParaCancelar.id}`, {
          ...this.agendamentoParaCancelar,
          status: 'cancelado'
        });

        this.showSuccess('Agendamento cancelado!');
        this.carregarAgendamentos();
        this.fecharModalCancelar();
      } catch (error) {
        console.error('Erro ao cancelar:', error);
        this.showError('Erro ao cancelar agendamento');
      } finally {
        this.cancelando = false;
      }
    },

    formatarDataGrupo(data) {
      if (!data) return 'Data inválida';

      // Garante que a data está no formato YYYY-MM-DD
      const dataParts = data.split('-');
      if (dataParts.length !== 3) return 'Data inválida';

      const [ano, mes, dia] = dataParts;
      const d = new Date(parseInt(ano), parseInt(mes) - 1, parseInt(dia));

      const hoje = new Date();
      hoje.setHours(0, 0, 0, 0);

      const amanha = new Date(hoje);
      amanha.setDate(hoje.getDate() + 1);

      const dataComparar = new Date(d);
      dataComparar.setHours(0, 0, 0, 0);

      if (dataComparar.getTime() === hoje.getTime()) {
        return 'Hoje - ' + d.toLocaleDateString('pt-BR', { day: '2-digit', month: 'long', year: 'numeric' });
      } else if (dataComparar.getTime() === amanha.getTime()) {
        return 'Amanhã - ' + d.toLocaleDateString('pt-BR', { day: '2-digit', month: 'long', year: 'numeric' });
      }

      return d.toLocaleDateString('pt-BR', {
        weekday: 'long',
        day: '2-digit',
        month: 'long',
        year: 'numeric'
      });
    },

    formatarData(data) {
      if (!data) return '-';

      const dataParts = data.split('-');
      if (dataParts.length !== 3) return '-';

      const [ano, mes, dia] = dataParts;
      const d = new Date(parseInt(ano), parseInt(mes) - 1, parseInt(dia));

      return d.toLocaleDateString('pt-BR', { day: '2-digit', month: 'long', year: 'numeric' });
    },

    getStatusLabel(status) {
      const labels = {
        pendente: 'Pendente',
        confirmado: 'Confirmado',
        concluido: 'Concluído',
        cancelado: 'Cancelado'
      };
      return labels[status] || status;
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
.agendamentos-container {
  padding: 0;
  background: #f5f7fa;
  min-height: 100vh;
}

.page-header {
  background: white;
  padding: 30px 40px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  margin-bottom: 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.page-header h1 {
  margin: 0;
  color: #55165e;
  font-size: 28px;
  display: flex;
  align-items: center;
  gap: 12px;
}

.btn-primary {
  padding: 12px 24px;
  background: linear-gradient(135deg, #55165e, #7a2087);
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(85, 22, 94, 0.3);
}

/* Filtros */
.filtros-container {
  max-width: 1400px;
  margin: 0 auto 30px;
  padding: 0 40px;
}

.filtros-card {
  background: white;
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 15px;
}

.filtro-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.filtro-group label {
  font-weight: 600;
  color: #333;
  font-size: 14px;
  display: flex;
  align-items: center;
  gap: 6px;
}

.form-control {
  padding: 10px 12px;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  font-size: 14px;
  transition: all 0.3s ease;
}

.form-control:focus {
  outline: none;
  border-color: #55165e;
  box-shadow: 0 0 0 3px rgba(85, 22, 94, 0.1);
}

.total-agendamentos {
  background: linear-gradient(135deg, #55165e, #7a2087);
  color: white;
  padding: 15px 20px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 16px;
}

.total-agendamentos i {
  font-size: 24px;
}

/* Lista de Agendamentos */
.agendamentos-content {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 40px 40px;
}

.loading {
  text-align: center;
  padding: 60px 20px;
  color: #666;
  font-size: 18px;
}

.loading i {
  font-size: 40px;
  display: block;
  margin-bottom: 15px;
  animation: spin 2s linear infinite;
}

@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.empty-state {
  text-align: center;
  padding: 60px 20px;
  background: white;
  border-radius: 12px;
}

.empty-state i {
  font-size: 80px;
  color: #ddd;
  margin-bottom: 20px;
}

.empty-state p {
  color: #666;
  font-size: 18px;
}

/* Agrupamento por Data */
.data-group {
  margin-bottom: 40px;
}

.data-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 20px;
  padding-bottom: 15px;
  border-bottom: 3px solid #55165e;
}

.data-header i {
  font-size: 28px;
  color: #55165e;
}

.data-header h2 {
  margin: 0;
  color: #333;
  font-size: 20px;
  text-transform: capitalize;
  flex: 1;
}

.count-badge {
  background: #55165e;
  color: white;
  padding: 6px 14px;
  border-radius: 20px;
  font-weight: 600;
  font-size: 14px;
}

.agendamentos-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 20px;
}

.agendamento-card {
  background: white;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
  border-left: 5px solid;
}

.agendamento-card.status-pendente {
  border-left-color: #ffc107;
}

.agendamento-card.status-confirmado {
  border-left-color: #17a2b8;
}

.agendamento-card.status-concluido {
  border-left-color: #28a745;
}

.agendamento-card.status-cancelado {
  border-left-color: #dc3545;
  opacity: 0.7;
}

.agendamento-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: start;
  margin-bottom: 15px;
  padding-bottom: 15px;
  border-bottom: 1px solid #f0f0f0;
}

.cliente-info h3 {
  margin: 0 0 5px 0;
  color: #333;
  font-size: 18px;
}

.telefone {
  margin: 0;
  color: #666;
  font-size: 14px;
  display: flex;
  align-items: center;
  gap: 5px;
}

.status-badge {
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
}

.status-badge.status-pendente {
  background: #fff3cd;
  color: #856404;
}

.status-badge.status-confirmado {
  background: #d1ecf1;
  color: #0c5460;
}

.status-badge.status-concluido {
  background: #d4edda;
  color: #155724;
}

.status-badge.status-cancelado {
  background: #f8d7da;
  color: #721c24;
}

.card-body {
  margin-bottom: 15px;
}

.info-row {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 8px 0;
  color: #555;
  font-size: 14px;
}

.info-row i {
  color: #55165e;
  font-size: 16px;
  width: 20px;
}

.info-row.observacoes {
  background: #f8f9fa;
  padding: 10px;
  border-radius: 6px;
  margin-top: 8px;
}

.card-actions {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.btn-action {
  flex: 1;
  min-width: 100px;
  padding: 8px 12px;
  border: none;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
}

.btn-confirmar {
  background: #17a2b8;
  color: white;
}

.btn-confirmar:hover {
  background: #138496;
  transform: translateY(-2px);
}

.btn-concluir {
  background: #28a745;
  color: white;
}

.btn-concluir:hover {
  background: #218838;
  transform: translateY(-2px);
}

.btn-cancelar {
  background: #dc3545;
  color: white;
}

.btn-cancelar:hover {
  background: #c82333;
  transform: translateY(-2px);
}

/* Modal de Cancelamento */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 3000;
}

.modal-delete {
  background: white;
  border-radius: 16px;
  padding: 40px;
  max-width: 500px;
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
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.05); }
}

.modal-delete h2 {
  color: #333;
  margin-bottom: 15px;
  font-size: 24px;
}

.modal-delete p {
  color: #666;
  margin-bottom: 10px;
}

.cliente-name {
  color: #55165e;
  font-weight: 700;
  font-size: 18px;
  margin: 15px 0;
}

.agendamento-details {
  background: #f8f9fa;
  padding: 15px;
  border-radius: 8px;
  margin: 20px 0;
  text-align: left;
}

.agendamento-details p {
  margin: 8px 0;
  color: #333;
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
}

.btn-cancel:disabled,
.btn-delete-confirm:disabled {
  opacity: 0.6;
  cursor: not-allowed;
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
@media (max-width: 768px) {
  .page-header {
    flex-direction: column;
    gap: 15px;
    align-items: stretch;
  }

  .filtros-card {
    grid-template-columns: 1fr;
  }

  .agendamentos-grid {
    grid-template-columns: 1fr;
  }

  .agendamentos-content,
  .filtros-container {
    padding: 0 20px 20px;
  }
}
</style>

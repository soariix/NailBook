<template>
  <div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="sidebar-header">
        <h1>💅 NailBook</h1>
      </div>
      <nav class="sidebar-nav">
        <router-link to="/dashboard" class="nav-item" active-class="active">
          <i class="bi bi-house-door"></i>
          <span>Dashboard</span>
        </router-link>
        <router-link to="/novo-agendamento" class="nav-item" active-class="active">
          <i class="bi bi-calendar-plus"></i>
          <span>Novo Agendamento</span>
        </router-link>
        <router-link to="/clientes" class="nav-item" active-class="active">
          <i class="bi bi-people"></i>
          <span>Clientes</span>
        </router-link>
        <router-link to="/servicos" class="nav-item" active-class="active">
          <i class="bi bi-scissors"></i>
          <span>Serviços</span>
        </router-link>
      </nav>
      <div class="sidebar-footer">
        <button @click="handleLogout" class="btn-logout">
          <i class="bi bi-box-arrow-right"></i>
          <span>Sair</span>
        </button>
      </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <div class="welcome-message">
        <h2>Bem-vinda, {{ userName }}!</h2>
        <p class="date">{{ currentDate }}</p>
      </div>

      <div class="dashboard-grid">
        <div class="dashboard-card" @click="$router.push('/novo-agendamento')">
          <i class="bi bi-calendar-check"></i>
          <h3>Agendamentos Hoje</h3>
          <p class="card-number">{{ stats.agendamentosHoje }}</p>
        </div>

        <div class="dashboard-card" @click="$router.push('/clientes')">
          <i class="bi bi-people"></i>
          <h3>Total de Clientes</h3>
          <p class="card-number">{{ stats.totalClientes }}</p>
        </div>

        <div class="dashboard-card" @click="$router.push('/servicos')">
          <i class="bi bi-scissors"></i>
          <h3>Serviços Cadastrados</h3>
          <p class="card-number">{{ stats.totalServicos }}</p>
        </div>

        <div class="dashboard-card action-card" @click="$router.push('/novo-agendamento')">
          <i class="bi bi-plus-circle"></i>
          <h3>Criar Agendamento</h3>
          <button class="btn-action">Novo</button>
        </div>
      </div>

      <!-- Agendamentos de Hoje -->
      <div class="section">
        <div class="section-header">
          <h2>Agendamentos de Hoje</h2>
        </div>
        <div v-if="loading" class="loading">Carregando...</div>
        <div v-else-if="agendamentosHoje.length === 0" class="empty-state">
          <i class="bi bi-calendar-x"></i>
          <p>Nenhum agendamento para hoje</p>
        </div>
        <div v-else class="agendamentos-list">
          <div v-for="agendamento in agendamentosHoje" :key="agendamento.id" class="agendamento-item">
            <div class="agendamento-time">
              <i class="bi bi-clock"></i>
              <span>{{ formatHora(agendamento.hora) }}</span>
            </div>
            <div class="agendamento-info">
              <h4>{{ agendamento.cliente_nome }}</h4>
              <p>{{ agendamento.servico }}</p>
            </div>
            <div class="agendamento-status" :class="agendamento.status">
              {{ getStatusLabel(agendamento.status) }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Dashboard',
  data() {
    return {
      userName: '',
      currentDate: '',
      loading: false,
      stats: {
        agendamentosHoje: 0,
        totalClientes: 0,
        totalServicos: 0
      },
      agendamentosHoje: []
    };
  },
  mounted() {
    const user = JSON.parse(localStorage.getItem('user') || '{}');
    this.userName = user.name || 'Usuária';
    this.setCurrentDate();
    this.loadDashboardData();
  },
  methods: {
    setCurrentDate() {
      const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
      this.currentDate = new Date().toLocaleDateString('pt-BR', options);
    },

    async loadDashboardData() {
      this.loading = true;
      try {
        // Buscar agendamentos de hoje
        const agendamentosResp = await axios.get('/api/agendamentos/hoje');
        this.agendamentosHoje = agendamentosResp.data;
        this.stats.agendamentosHoje = this.agendamentosHoje.length;

        // Buscar total de clientes
        const clientesResp = await axios.get('/api/clientes');
        this.stats.totalClientes = clientesResp.data.length;

        // Buscar total de serviços
        const servicosResp = await axios.get('/api/servicos');
        this.stats.totalServicos = servicosResp.data.length;
      } catch (error) {
        console.error('Erro ao carregar dados do dashboard:', error);

        // Se erro de autenticação, redirecionar para login
        if (error.response && error.response.status === 401) {
          localStorage.removeItem('auth_token');
          this.$router.push('/');
        }
      } finally {
        this.loading = false;
      }
    },

    formatHora(hora) {
      if (!hora) return '';
      // Se for timestamp completo, extrair apenas HH:mm
      return hora.substring(11, 16);
    },

    getStatusLabel(status) {
      const labels = {
        'pendente': 'Pendente',
        'confirmado': 'Confirmado',
        'concluido': 'Concluído',
        'cancelado': 'Cancelado'
      };
      return labels[status] || status;
    },

    async handleLogout() {
      try {
        await axios.post('/api/logout');
      } catch (error) {
        console.error('Erro ao fazer logout:', error);
      } finally {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user');
        delete axios.defaults.headers.common['Authorization'];
        this.$router.push('/');
      }
    }
  }
};
</script>

<style scoped>
.dashboard-container {
  display: flex;
  min-height: 100vh;
  background: #f5f7fa;
}

/* Sidebar */
.sidebar {
  width: 260px;
  background: #55165e;
  color: white;
  display: flex;
  flex-direction: column;
  position: fixed;
  height: 100vh;
  left: 0;
  top: 0;
}

.sidebar-header {
  padding: 30px 20px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar-header h1 {
  margin: 0;
  font-size: 24px;
  text-align: center;
}

.sidebar-nav {
  flex: 1;
  padding: 20px 0;
}

.nav-item {
  display: flex;
  align-items: center;
  padding: 15px 20px;
  color: rgba(255, 255, 255, 0.8);
  text-decoration: none;
  transition: all 0.3s;
  gap: 15px;
  font-size: 16px;
}

.nav-item i {
  font-size: 20px;
  width: 24px;
}

.nav-item:hover,
.nav-item.active {
  background: rgba(255, 255, 255, 0.1);
  color: white;
}

.sidebar-footer {
  padding: 20px;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.btn-logout {
  width: 100%;
  background: rgba(255, 255, 255, 0.1);
  color: white;
  border: none;
  padding: 12px;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  font-size: 16px;
}

.btn-logout:hover {
  background: rgba(255, 255, 255, 0.2);
}

/* Main Content */
.main-content {
  margin-left: 260px;
  flex: 1;
  padding: 30px;
}

.welcome-message {
  background: white;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  margin-bottom: 30px;
}

.welcome-message h2 {
  color: #55165e;
  margin: 0 0 5px 0;
  font-size: 28px;
}

.welcome-message .date {
  color: #666;
  margin: 0;
  text-transform: capitalize;
}

.dashboard-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.dashboard-card {
  background: white;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  text-align: center;
  transition: all 0.3s;
  cursor: pointer;
}

.dashboard-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 5px 20px rgba(85, 22, 94, 0.2);
}

.dashboard-card i {
  font-size: 48px;
  color: #55165e;
  margin-bottom: 15px;
}

.dashboard-card h3 {
  color: #333;
  margin-bottom: 15px;
  font-size: 16px;
}

.card-number {
  font-size: 48px;
  font-weight: bold;
  color: #55165e;
  margin: 0;
}

.action-card {
  background: linear-gradient(135deg, #55165e 0%, #7a1f85 100%);
  color: white;
}

.action-card i,
.action-card h3 {
  color: white;
}

.btn-action {
  background: white;
  color: #55165e;
  border: none;
  padding: 10px 30px;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s;
  font-size: 16px;
  font-weight: bold;
  margin-top: 10px;
}

.btn-action:hover {
  transform: scale(1.05);
}

/* Seção de Agendamentos */
.section {
  background: white;
  border-radius: 10px;
  padding: 30px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.section-header {
  margin-bottom: 20px;
  padding-bottom: 15px;
  border-bottom: 2px solid #f0f0f0;
}

.section-header h2 {
  color: #55165e;
  margin: 0;
  font-size: 24px;
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
  margin-bottom: 15px;
}

.empty-state p {
  color: #666;
  margin: 0;
}

.agendamentos-list {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.agendamento-item {
  display: flex;
  align-items: center;
  padding: 20px;
  background: #f8f9fa;
  border-radius: 8px;
  transition: all 0.3s;
}

.agendamento-item:hover {
  background: #e9ecef;
  transform: translateX(5px);
}

.agendamento-time {
  display: flex;
  align-items: center;
  gap: 8px;
  min-width: 100px;
  color: #55165e;
  font-weight: bold;
  font-size: 18px;
}

.agendamento-time i {
  font-size: 20px;
}

.agendamento-info {
  flex: 1;
  margin-left: 20px;
}

.agendamento-info h4 {
  margin: 0 0 5px 0;
  color: #333;
  font-size: 18px;
}

.agendamento-info p {
  margin: 0;
  color: #666;
  font-size: 14px;
}

.agendamento-status {
  padding: 6px 16px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: bold;
  text-transform: uppercase;
}

.agendamento-status.pendente {
  background: #fff3cd;
  color: #856404;
}

.agendamento-status.confirmado {
  background: #d1ecf1;
  color: #0c5460;
}

.agendamento-status.concluido {
  background: #d4edda;
  color: #155724;
}

.agendamento-status.cancelado {
  background: #f8d7da;
  color: #721c24;
}

.btn-logout {
  background: #55165e;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-logout:hover {
  background: #7a1f85;
  transform: translateY(-2px);
}

.welcome-message {
  background: white;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  margin-bottom: 30px;
  text-align: center;
}

.welcome-message h2 {
  color: #55165e;
  margin-bottom: 10px;
}

.welcome-message p {
  color: #666;
}

.dashboard-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
}

.dashboard-card {
  background: white;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  text-align: center;
  transition: transform 0.3s;
}

.dashboard-card:hover {
  transform: translateY(-5px);
}

.dashboard-card i {
  font-size: 48px;
  color: #55165e;
  margin-bottom: 15px;
}

.dashboard-card h3 {
  color: #333;
  margin-bottom: 15px;
  font-size: 18px;
}

.card-number {
  font-size: 36px;
  font-weight: bold;
  color: #55165e;
  margin: 0;
}

.btn-action {
  background: #55165e;
  color: white;
  border: none;
  padding: 10px 30px;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s;
  font-size: 16px;
  margin-top: 10px;
}

.btn-action:hover {
  background: #7a1f85;
  transform: translateY(-2px);
}
</style>

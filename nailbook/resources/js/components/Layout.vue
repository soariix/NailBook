<template>
  <div class="layout-container">
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
        <router-link to="/agendamentos" class="nav-item" active-class="active">
          <i class="bi bi-calendar-check"></i>
          <span>Agendamentos</span>
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
        <router-link to="/perfil" class="user-info" active-class="active">
          <i class="bi bi-person-circle"></i>
          <span>{{ userName }}</span>
        </router-link>
        <button @click="handleLogout" class="btn-logout">
          <i class="bi bi-box-arrow-right"></i>
          <span>Sair</span>
        </button>
      </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <slot></slot>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Layout',
  data() {
    return {
      userName: ''
    };
  },
  mounted() {
    const user = JSON.parse(localStorage.getItem('user') || '{}');
    this.userName = user.name || 'Usuária';
  },
  methods: {
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
.layout-container {
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

.user-info {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 5px;
  margin-bottom: 10px;
  color: white;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.3s ease;
}

.user-info:hover,
.user-info.active {
  background: rgba(255, 255, 255, 0.2);
  transform: translateX(5px);
}

.user-info i {
  font-size: 24px;
}

.user-info span {
  font-size: 14px;
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
  min-height: 100vh;
}
</style>

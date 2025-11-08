import { createRouter, createWebHistory } from 'vue-router';
import Login from '../components/Login.vue';
import Dashboard from '../components/Dashboard.vue';
import NovoAgendamento from '../components/NovoAgendamento.vue';
import Agendamentos from '../components/Agendamentos.vue';
import Clientes from '../components/Clientes.vue';
import Servicos from '../components/Servicos.vue';
import Perfil from '../components/Perfil.vue';

const routes = [
  {
    path: '/',
    name: 'Login',
    component: Login
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: Dashboard,
    meta: { requiresAuth: true }
  },
  {
    path: '/novo-agendamento',
    name: 'NovoAgendamento',
    component: NovoAgendamento,
    meta: { requiresAuth: true }
  },
  {
    path: '/agendamentos',
    name: 'Agendamentos',
    component: Agendamentos,
    meta: { requiresAuth: true }
  },
  {
    path: '/clientes',
    name: 'Clientes',
    component: Clientes,
    meta: { requiresAuth: true }
  },
  {
    path: '/servicos',
    name: 'Servicos',
    component: Servicos,
    meta: { requiresAuth: true }
  },
  {
    path: '/perfil',
    name: 'Perfil',
    component: Perfil,
    meta: { requiresAuth: true }
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

// Guard para rotas protegidas
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('auth_token');

  if (to.meta.requiresAuth && !token) {
    next({ name: 'Login' });
  } else if (to.name === 'Login' && token) {
    next({ name: 'Dashboard' });
  } else {
    next();
  }
});

export default router;

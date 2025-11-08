<template>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <div class="user-icon">
          <i class="bi bi-person-circle"></i>
        </div>
        <h2>User Login</h2>
      </div>

      <form @submit.prevent="handleLogin">
        <div class="form-group">
          <i class="bi bi-envelope input-icon"></i>
          <input
            type="email"
            v-model="form.email"
            placeholder="Email ID"
            required
            class="form-control"
          />
        </div>

        <div class="form-group">
          <i class="bi bi-lock input-icon"></i>
          <input
            type="password"
            v-model="form.password"
            placeholder="Password"
            required
            class="form-control"
          />
        </div>

        <div class="form-options">
          <label class="remember-me">
            <input type="checkbox" v-model="form.remember" />
            Remember me
          </label>
          <a href="#" class="forgot-password">Forgot Password?</a>
        </div>

        <button type="submit" class="btn-login" :disabled="loading">
          {{ loading ? 'LOADING...' : 'LOGIN' }}
        </button>

        <div v-if="error" class="alert alert-danger mt-3">
          {{ error }}
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Login',
  data() {
    return {
      form: {
        email: '',
        password: '',
        remember: false
      },
      loading: false,
      error: null
    };
  },
  methods: {
    async handleLogin() {
      this.loading = true;
      this.error = null;

      try {
        const response = await axios.post('/api/login', {
          email: this.form.email,
          password: this.form.password
        });

        // Salvar token no localStorage
        localStorage.setItem('auth_token', response.data.access_token);
        localStorage.setItem('user', JSON.stringify(response.data.user));

        // Configurar token no Axios para próximas requisições
        axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.access_token}`;

        // Redirecionar para o dashboard
        this.$router.push('/dashboard');
      } catch (err) {
        this.error = err.response?.data?.message || 'Erro ao fazer login. Verifique suas credenciais.';
        console.error('Erro no login:', err);
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>

<style scoped>
.login-container {
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background: linear-gradient(135deg, #55165e 0%, #7a1f85 50%, #9fd3c7 100%);
}

.login-card {
  background: rgba(255, 255, 255, 0.95);
  border-radius: 20px;
  padding: 40px;
  width: 100%;
  max-width: 400px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
  backdrop-filter: blur(10px);
}

.login-header {
  text-align: center;
  margin-bottom: 30px;
}

.user-icon {
  font-size: 80px;
  color: #55165e;
  margin-bottom: 10px;
}

.login-header h2 {
  color: #55165e;
  font-weight: 300;
  letter-spacing: 3px;
  margin: 0;
  text-transform: uppercase;
}

.form-group {
  position: relative;
  margin-bottom: 25px;
}

.input-icon {
  position: absolute;
  left: 15px;
  top: 50%;
  transform: translateY(-50%);
  color: #55165e;
  font-size: 18px;
}

.form-control {
  width: 100%;
  padding: 12px 15px 12px 45px;
  border: none;
  border-bottom: 2px solid #55165e;
  background: transparent;
  font-size: 14px;
  outline: none;
  transition: border-color 0.3s;
  color: #333;
}

.form-control::placeholder {
  color: rgba(85, 22, 94, 0.5);
}

.form-control:focus {
  border-bottom-color: #7a1f85;
}

.form-options {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
  font-size: 12px;
}

.remember-me {
  display: flex;
  align-items: center;
  gap: 5px;
  color: #666;
  cursor: pointer;
}

.remember-me input[type="checkbox"] {
  cursor: pointer;
}

.forgot-password {
  color: #55165e;
  text-decoration: none;
  transition: color 0.3s;
}

.forgot-password:hover {
  text-decoration: underline;
  color: #7a1f85;
}

.btn-login {
  width: 100%;
  padding: 12px;
  background: #55165e;
  color: white;
  border: none;
  border-radius: 5px;
  font-weight: bold;
  letter-spacing: 2px;
  cursor: pointer;
  transition: all 0.3s;
  font-size: 14px;
}

.btn-login:hover:not(:disabled) {
  background: #7a1f85;
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(85, 22, 94, 0.3);
}

.btn-login:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.alert {
  padding: 10px;
  border-radius: 5px;
  text-align: center;
  font-size: 13px;
}

.alert-danger {
  background-color: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}

.mt-3 {
  margin-top: 15px;
}
</style>

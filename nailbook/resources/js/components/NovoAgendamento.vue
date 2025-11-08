<template>
  <Layout>
    <div class="novo-agendamento-container">
      <!-- Header -->
      <div class="page-header">
        <div class="header-left">
          <button @click="voltarDashboard" class="btn-voltar">
            <i class="bi bi-arrow-left"></i>
          </button>
          <div>
            <h1><i class="bi bi-calendar-plus"></i> Novo Agendamento</h1>
            <p class="subtitle">Preencha os dados abaixo para criar um agendamento</p>
          </div>
        </div>
      </div>

      <!-- Formulário -->
      <div class="form-container">
        <form @submit.prevent="criarAgendamento">

          <!-- Progresso do formulário -->
          <div class="progress-steps">
            <div class="step" :class="{ active: currentStep >= 1, completed: currentStep > 1 }">
              <div class="step-number">1</div>
              <span>Cliente</span>
            </div>
            <div class="step-line" :class="{ active: currentStep > 1 }"></div>
            <div class="step" :class="{ active: currentStep >= 2, completed: currentStep > 2 }">
              <div class="step-number">2</div>
              <span>Serviço</span>
            </div>
            <div class="step-line" :class="{ active: currentStep > 2 }"></div>
            <div class="step" :class="{ active: currentStep >= 3 }">
              <div class="step-number">3</div>
              <span>Data/Hora</span>
            </div>
          </div>

          <!-- Step 1: Cliente -->
          <div v-show="currentStep === 1" class="form-step">
            <div class="card">
              <div class="card-header">
                <i class="bi bi-person-circle"></i>
                <h2>Informações do Cliente</h2>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label for="cliente_nome">
                    Nome Completo *
                    <span class="hint">Digite o nome da cliente</span>
                  </label>
                  <div class="input-with-icon">
                    <i class="bi bi-person"></i>
                    <input
                      type="text"
                      id="cliente_nome"
                      v-model="form.cliente_nome"
                      class="form-control"
                      required
                      placeholder="Ex: Maria Silva"
                      @input="validateStep1"
                    />
                  </div>
                </div>

                <div class="form-group">
                  <label for="cliente_telefone">
                    Telefone *
                    <span class="hint">Para contato e confirmação</span>
                  </label>
                  <div class="input-with-icon">
                    <i class="bi bi-telephone"></i>
                    <input
                    type="tel"
                    id="cliente_telefone"
                    v-model="form.cliente_telefone"
                    class="form-control"
                    required
                    placeholder="(00) 00000-0000"
                    @input="formatPhoneNumber"
                    maxlength="15"
                    />

                  </div>
                </div>
              </div>
            </div>

            <div class="form-actions">
              <button type="button" @click="nextStep" class="btn-next" :disabled="!step1Valid">
                Próximo <i class="bi bi-arrow-right"></i>
              </button>
            </div>
          </div>

          <!-- Step 2: Serviço -->
          <div v-show="currentStep === 2" class="form-step">
            <div class="card">
              <div class="card-header">
                <i class="bi bi-scissors"></i>
                <h2>Escolha os Serviços</h2>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label>
                    Serviços Desejados *
                    <span class="hint">Selecione um ou mais serviços</span>
                  </label>

                  <div class="servicos-grid">
                    <div
                      v-for="servico in servicosDisponiveis"
                      :key="servico.nome"
                      class="servico-card"
                      :class="{ selected: isServicoSelecionado(servico.nome) }"
                      @click="toggleServico(servico.nome)"
                    >
                      <div class="servico-checkbox">
                        <i class="bi" :class="isServicoSelecionado(servico.nome) ? 'bi-check-circle-fill' : 'bi-circle'"></i>
                      </div>
                      <div class="servico-icon">{{ servico.icone }}</div>
                      <div class="servico-nome">{{ servico.nome }}</div>
                      <div class="servico-preco">R$ {{ servico.preco.toFixed(2).replace('.', ',') }}</div>
                    </div>
                  </div>
                </div>

                <div v-if="servicosSelecionados.length > 0" class="servicos-selecionados">
                  <h3><i class="bi bi-check2-circle"></i> Serviços Selecionados</h3>
                  <div class="lista-servicos">
                    <div v-for="servico in servicosSelecionados" :key="servico" class="servico-item">
                      <span>{{ servico }}</span>
                      <span class="preco">R$ {{ getPrecoServico(servico).toFixed(2).replace('.', ',') }}</span>
                      <button type="button" @click="toggleServico(servico)" class="btn-remove">
                        <i class="bi bi-x-circle"></i>
                      </button>
                    </div>
                  </div>
                  <div class="valor-total">
                    <strong>Valor Total:</strong>
                    <span class="total">R$ {{ valorTotal.toFixed(2).replace('.', ',') }}</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-actions">
              <button type="button" @click="prevStep" class="btn-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
              </button>
              <button type="button" @click="nextStep" class="btn-next" :disabled="!step2Valid">
                Próximo <i class="bi bi-arrow-right"></i>
              </button>
            </div>
          </div>

          <!-- Step 3: Data e Hora -->
          <div v-show="currentStep === 3" class="form-step">
            <div class="card">
              <div class="card-header">
                <i class="bi bi-calendar-event"></i>
                <h2>Agendar Data e Hora</h2>
              </div>
              <div class="card-body">
                <div class="form-row">
                  <div class="form-group">
                    <label for="data">
                      Data do Agendamento *
                      <span class="hint">Escolha o dia</span>
                    </label>
                    <div class="input-with-icon">
                      <i class="bi bi-calendar3"></i>
                      <input
                        type="date"
                        id="data"
                        v-model="form.data"
                        class="form-control"
                        required
                        :min="minDate"
                      />
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="hora">
                      Horário *
                      <span class="hint">Escolha a hora</span>
                    </label>
                    <div class="input-with-icon">
                      <i class="bi bi-clock"></i>
                      <input
                        type="time"
                        id="hora"
                        v-model="form.hora"
                        class="form-control"
                        required
                      />
                    </div>

                    <!-- Aviso de horários ocupados -->
                    <div v-if="horariosOcupados.length > 0" class="horarios-ocupados-aviso">
                      <i class="bi bi-exclamation-circle"></i>
                      <strong>Horários já ocupados nesta data:</strong>
                      <div class="horarios-lista">
                        <span v-for="hora in horariosOcupados" :key="hora" class="horario-ocupado">
                          {{ hora }}
                        </span>
                      </div>
                    </div>

                    <!-- Sugestões de horários disponíveis -->
                    <div v-if="form.data && horariosOcupados.length > 0" class="horarios-disponiveis">
                      <strong><i class="bi bi-check-circle"></i> Horários disponíveis sugeridos:</strong>
                      <div class="horarios-lista">
                        <button
                          v-for="hora in horariosDisponiveis"
                          :key="hora"
                          type="button"
                          @click="form.hora = hora"
                          class="btn-horario"
                          :class="{ selected: form.hora === hora }"
                        >
                          {{ hora }}
                        </button>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="observacoes">
                    Observações
                    <span class="hint">Informações adicionais (opcional)</span>
                  </label>
                  <div class="input-with-icon">
                    <i class="bi bi-chat-text"></i>
                    <textarea
                      id="observacoes"
                      v-model="form.observacoes"
                      class="form-control"
                      rows="4"
                      placeholder="Ex: Cliente preferencial, alergia a produtos, observações especiais..."
                    ></textarea>
                  </div>
                </div>

                <!-- Resumo do Agendamento -->
                <div class="resumo-card">
                  <h3><i class="bi bi-check-circle"></i> Resumo do Agendamento</h3>
                  <div class="resumo-item">
                    <i class="bi bi-person"></i>
                    <div>
                      <strong>Cliente:</strong>
                      <span>{{ form.cliente_nome || '-' }}</span>
                    </div>
                  </div>
                  <div class="resumo-item">
                    <i class="bi bi-telephone"></i>
                    <div>
                      <strong>Telefone:</strong>
                      <span>{{ form.cliente_telefone || '-' }}</span>
                    </div>
                  </div>
                  <div class="resumo-item">
                    <i class="bi bi-scissors"></i>
                    <div>
                      <strong>Serviços:</strong>
                      <div class="servicos-resumo">
                        <div v-for="servico in servicosSelecionados" :key="servico" class="servico-resumo-item">
                          {{ servico }} - R$ {{ getPrecoServico(servico).toFixed(2).replace('.', ',') }}
                        </div>
                        <div v-if="servicosSelecionados.length === 0">-</div>
                      </div>
                    </div>
                  </div>
                  <div class="resumo-item">
                    <i class="bi bi-currency-dollar"></i>
                    <div>
                      <strong>Valor Total:</strong>
                      <span class="valor-destaque">R$ {{ valorTotal.toFixed(2).replace('.', ',') }}</span>
                    </div>
                  </div>
                  <div class="resumo-item">
                    <i class="bi bi-calendar-event"></i>
                    <div>
                      <strong>Data/Hora:</strong>
                      <span>{{ formatDateTime }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-actions">
              <button type="button" @click="prevStep" class="btn-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
              </button>
              <button type="submit" class="btn-submit" :disabled="loading">
                <i class="bi bi-check-circle"></i>
                {{ loading ? 'Criando...' : 'Confirmar Agendamento' }}
              </button>
            </div>
          </div>

        </form>
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
  name: 'NovoAgendamento',
  components: {
    Layout
  },
  data() {
    return {
      currentStep: 1,
      step1Valid: false,
      step2Valid: false,
      servicosSelecionados: [],
      form: {
        cliente_nome: '',
        cliente_telefone: '',
        servico: '',
        valor: '',
        data: '',
        hora: '',
        observacoes: '',
        status: 'pendente'
      },
      servicosDisponiveis: [
        { nome: 'Manicure', icone: '💅', preco: 30.00 },
        { nome: 'Pedicure', icone: '🦶', preco: 35.00 },
        { nome: 'Manicure + Pedicure', icone: '💅🦶', preco: 60.00 },
        { nome: 'Esmaltação em Gel', icone: '✨', preco: 50.00 },
        { nome: 'Unhas em Gel', icone: '💎', preco: 80.00 },
        { nome: 'Unhas em Fibra', icone: '🌟', preco: 90.00 },
        { nome: 'Alongamento', icone: '📏', preco: 100.00 },
        { nome: 'Blindagem', icone: '🛡️', preco: 45.00 },
        { nome: 'Spa dos Pés', icone: '🧖‍♀️', preco: 70.00 },
        { nome: 'Design de Unhas', icone: '🎨', preco: 40.00 }
      ],
      servicosPrecos: {
        'Manicure': 30.00,
        'Pedicure': 35.00,
        'Manicure + Pedicure': 60.00,
        'Esmaltação em Gel': 50.00,
        'Unhas em Gel': 80.00,
        'Unhas em Fibra': 90.00,
        'Alongamento': 100.00,
        'Blindagem': 45.00,
        'Spa dos Pés': 70.00,
        'Design de Unhas': 40.00
      },
      loading: false,
      successMessage: '',
      errorMessage: '',
      agendamentosExistentes: [],
      horariosOcupados: []
    };
  },
  computed: {
    valorTotal() {
      return this.servicosSelecionados.reduce((total, servico) => {
        return total + this.getPrecoServico(servico);
      }, 0);
    },

    horariosDisponiveis() {
      const horarios = [
        '08:00', '08:30', '09:00', '09:30', '10:00', '10:30',
        '11:00', '11:30', '12:00', '12:30', '13:00', '13:30',
        '14:00', '14:30', '15:00', '15:30', '16:00', '16:30',
        '17:00', '17:30', '18:00', '18:30', '19:00', '19:30'
      ];

      return horarios.filter(hora => !this.horariosOcupados.includes(hora));
    },

    minDate() {
      const today = new Date();
      return today.toISOString().split('T')[0];
    },
    formatDateTime() {
      if (!this.form.data || !this.form.hora) return '-';

      const date = new Date(this.form.data + 'T00:00:00');
      const dateStr = date.toLocaleDateString('pt-BR');
      return `${dateStr} às ${this.form.hora}`;
    }
  },
  methods: {
    formatPhoneNumber(event) {
      let value = event.target.value.replace(/\D/g, ''); // Remove tudo que não é número

      if (value.length <= 10) {
        // Formato: (00) 0000-0000
        value = value.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
      } else {
        // Formato: (00) 00000-0000
        value = value.replace(/^(\d{2})(\d{5})(\d{0,4}).*/, '($1) $2-$3');
      }

      this.form.cliente_telefone = value;
      this.validateStep1();
    },

    validateStep1() {
      this.step1Valid = this.form.cliente_nome.trim() !== '' &&
                        this.form.cliente_telefone.trim() !== '';
    },

    validateStep2() {
      this.step2Valid = this.servicosSelecionados.length > 0;
    },

    isServicoSelecionado(servico) {
      return this.servicosSelecionados.includes(servico);
    },

    toggleServico(servico) {
      const index = this.servicosSelecionados.indexOf(servico);
      if (index > -1) {
        this.servicosSelecionados.splice(index, 1);
      } else {
        this.servicosSelecionados.push(servico);
      }
      this.validateStep2();
    },

    getPrecoServico(servico) {
      const servicoObj = this.servicosDisponiveis.find(s => s.nome === servico);
      return servicoObj ? servicoObj.preco : 0;
    },

    nextStep() {
      if (this.currentStep === 1 && this.step1Valid) {
        this.currentStep = 2;
      } else if (this.currentStep === 2 && this.step2Valid) {
        this.currentStep = 3;
      }
    },

    prevStep() {
      if (this.currentStep > 1) {
        this.currentStep--;
      }
    },

    async criarAgendamento() {
      this.loading = true;
      this.errorMessage = '';
      this.successMessage = '';

      try {
        // Preparar dados do agendamento com múltiplos serviços
        const agendamentoData = {
          ...this.form,
          servico: this.servicosSelecionados.join(', '),
          valor: this.valorTotal
        };

        const response = await axios.post('/api/agendamentos', agendamentoData);

        this.successMessage = 'Agendamento criado com sucesso!';

        setTimeout(() => {
          this.$router.push('/dashboard');
        }, 1500);
      } catch (error) {
        console.error('Erro ao criar agendamento:', error);

        // Se erro de autenticação, redirecionar para login
        if (error.response && error.response.status === 401) {
          this.errorMessage = 'Sessão expirada. Por favor, faça login novamente.';
          setTimeout(() => {
            localStorage.removeItem('auth_token');
            this.$router.push('/');
          }, 2000);
        } else {
          this.errorMessage = error.response?.data?.message || 'Erro ao criar agendamento. Tente novamente.';
        }
      } finally {
        this.loading = false;
      }
    },

    voltarDashboard() {
      this.$router.push('/dashboard');
    },

    async carregarAgendamentos() {
      try {
        const response = await axios.get('/api/agendamentos');
        this.agendamentosExistentes = response.data;
        this.atualizarHorariosOcupados();
      } catch (error) {
        console.error('Erro ao carregar agendamentos:', error);

        // Se erro de autenticação, redirecionar para login
        if (error.response && error.response.status === 401) {
          localStorage.removeItem('auth_token');
          this.$router.push('/');
        }
      }
    },

    atualizarHorariosOcupados() {
      if (!this.form.data) return;

      this.horariosOcupados = this.agendamentosExistentes
        .filter(agendamento => agendamento.data === this.form.data && agendamento.status !== 'cancelado')
        .map(agendamento => agendamento.hora);
    },

    validarDataHora() {
      const dataHora = `${this.form.data} ${this.form.hora}`;
      const jaOcupado = this.agendamentosExistentes.some(
        agendamento =>
          agendamento.data === this.form.data &&
          agendamento.hora === this.form.hora &&
          agendamento.status !== 'cancelado'
      );

      if (jaOcupado) {
        this.errorMessage = 'Este horário já está ocupado. Por favor, escolha outro horário.';
        this.form.hora = '';
        return false;
      }

      this.errorMessage = '';
      return true;
    }
  },
  mounted() {
    // Definir data de hoje como padrão
    const today = new Date();
    this.form.data = today.toISOString().split('T')[0];

    // Carregar agendamentos existentes
    this.carregarAgendamentos();
  },
  watch: {
    'form.data'() {
      this.atualizarHorariosOcupados();
      this.form.hora = ''; // Limpar hora ao mudar data
    },
    'form.hora'() {
      if (this.form.hora) {
        this.validarDataHora();
      }
    }
  }
};
</script>


<style scoped>
.novo-agendamento-container {
  padding: 0;
  background: #f5f7fa;
  min-height: 100vh;
}

/* Header */
.page-header {
  background: white;
  padding: 25px 40px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  margin-bottom: 30px;
}

.header-left {
  display: flex;
  align-items: center;
  gap: 20px;
}

.btn-voltar {
  background: #55165e;
  color: white;
  border: none;
  width: 45px;
  height: 45px;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s;
  font-size: 20px;
}

.btn-voltar:hover {
  background: #7a1f85;
  transform: translateX(-3px);
}

.page-header h1 {
  color: #55165e;
  margin: 0 0 5px 0;
  font-size: 28px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.subtitle {
  color: #666;
  margin: 0;
  font-size: 14px;
}

/* Formulário */
.form-container {
  max-width: 900px;
  margin: 0 auto;
  padding: 0 20px 40px 20px;
}

/* Progresso */
.progress-steps {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 40px;
  padding: 30px;
  background: white;
  border-radius: 15px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.step {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
}

.step-number {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background: #e0e0e0;
  color: #999;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 18px;
  transition: all 0.3s;
}

.step.active .step-number {
  background: #55165e;
  color: white;
  transform: scale(1.1);
}

.step.completed .step-number {
  background: #28a745;
  color: white;
}

.step span {
  font-size: 14px;
  color: #999;
  font-weight: 500;
}

.step.active span {
  color: #55165e;
  font-weight: 600;
}

.step-line {
  width: 100px;
  height: 3px;
  background: #e0e0e0;
  margin: 0 10px;
  transition: all 0.3s;
}

.step-line.active {
  background: #55165e;
}

/* Card */
.card {
  background: white;
  border-radius: 15px;
  box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
  margin-bottom: 20px;
  overflow: hidden;
}

.card-header {
  background: linear-gradient(135deg, #55165e 0%, #7a1f85 100%);
  color: white;
  padding: 25px 30px;
  display: flex;
  align-items: center;
  gap: 15px;
}

.card-header i {
  font-size: 28px;
}

.card-header h2 {
  margin: 0;
  font-size: 22px;
  font-weight: 600;
}

.card-body {
  padding: 30px;
}

/* Form Groups */
.form-group {
  margin-bottom: 25px;
}

.form-group label {
  display: flex;
  flex-direction: column;
  gap: 5px;
  margin-bottom: 10px;
  color: #333;
  font-weight: 600;
  font-size: 15px;
}

.hint {
  font-size: 12px;
  color: #999;
  font-weight: 400;
}

.input-with-icon {
  position: relative;
}

.input-with-icon i {
  position: absolute;
  left: 15px;
  top: 50%;
  transform: translateY(-50%);
  color: #55165e;
  font-size: 18px;
}

.input-with-icon .form-control {
  padding-left: 45px;
}

.form-control {
  width: 100%;
  padding: 14px 18px;
  border: 2px solid #e0e0e0;
  border-radius: 10px;
  font-size: 15px;
  transition: all 0.3s;
  background: #fafafa;
}

.form-control:focus {
  outline: none;
  border-color: #55165e;
  background: white;
  box-shadow: 0 0 0 4px rgba(85, 22, 94, 0.1);
}

.form-control::placeholder {
  color: #aaa;
}

select.form-control {
  cursor: pointer;
}

textarea.form-control {
  resize: vertical;
  font-family: inherit;
  min-height: 100px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.valor-display {
  margin-top: 10px;
  padding: 12px 15px;
  background: #f0f9ff;
  border-left: 4px solid #55165e;
  border-radius: 5px;
  color: #333;
  font-size: 16px;
}

.valor-display strong {
  color: #55165e;
  font-size: 18px;
}

/* Resumo */
.resumo-card {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-radius: 12px;
  padding: 25px;
  margin-top: 25px;
  border: 2px solid #55165e;
}

.resumo-card h3 {
  color: #55165e;
  margin: 0 0 20px 0;
  font-size: 18px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.resumo-item {
  display: flex;
  align-items: flex-start;
  gap: 15px;
  margin-bottom: 15px;
  padding: 12px;
  background: white;
  border-radius: 8px;
}

.resumo-item:last-child {
  margin-bottom: 0;
}

.resumo-item i {
  color: #55165e;
  font-size: 20px;
  margin-top: 3px;
}

.resumo-item div {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.resumo-item strong {
  color: #666;
  font-size: 13px;
  font-weight: 600;
}

.resumo-item span {
  color: #333;
  font-size: 15px;
}

.valor-destaque {
  color: #55165e !important;
  font-weight: 700 !important;
  font-size: 18px !important;
}

/* Actions */
.form-actions {
  display: flex;
  gap: 15px;
  justify-content: space-between;
  margin-top: 30px;
}

.btn-secondary {
  background: #6c757d;
  color: white;
  border: none;
  padding: 14px 30px;
  border-radius: 10px;
  cursor: pointer;
  font-size: 16px;
  font-weight: 600;
  transition: all 0.3s;
  display: flex;
  align-items: center;
  gap: 8px;
}

.btn-secondary:hover {
  background: #5a6268;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-next {
  background: #55165e;
  color: white;
  border: none;
  padding: 14px 35px;
  border-radius: 10px;
  cursor: pointer;
  font-size: 16px;
  font-weight: 600;
  transition: all 0.3s;
  display: flex;
  align-items: center;
  gap: 8px;
}

.btn-next:hover:not(:disabled) {
  background: #7a1f85;
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(85, 22, 94, 0.3);
}

.btn-next:disabled {
  background: #ccc;
  cursor: not-allowed;
  opacity: 0.6;
}

.btn-submit {
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
  color: white;
  border: none;
  padding: 14px 40px;
  border-radius: 10px;
  cursor: pointer;
  font-size: 16px;
  font-weight: 600;
  transition: all 0.3s;
  display: flex;
  align-items: center;
  gap: 10px;
}

.btn-submit:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 5px 20px rgba(40, 167, 69, 0.4);
}

.btn-submit:disabled {
  background: #ccc;
  cursor: not-allowed;
  opacity: 0.6;
}

/* Alertas */
.alert {
  position: fixed;
  top: 20px;
  right: 20px;
  padding: 18px 25px;
  border-radius: 10px;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
  z-index: 3000;
  animation: slideIn 0.3s ease;
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 15px;
  font-weight: 500;
}

.alert i {
  font-size: 20px;
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

/* Grid de Serviços */
.servicos-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
  gap: 15px;
  margin-top: 15px;
}

.servico-card {
  background: white;
  border: 2px solid #e0e0e0;
  border-radius: 12px;
  padding: 20px 15px;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
}

.servico-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 4px 12px rgba(85, 22, 94, 0.15);
  border-color: #55165e;
}

.servico-card.selected {
  background: linear-gradient(135deg, #55165e 0%, #7a2087 100%);
  border-color: #55165e;
  color: white;
}

.servico-checkbox {
  position: absolute;
  top: 10px;
  right: 10px;
  font-size: 20px;
  color: #ccc;
}

.servico-card.selected .servico-checkbox {
  color: #fff;
}

.servico-icon {
  font-size: 40px;
  margin-bottom: 10px;
}

.servico-nome {
  font-size: 14px;
  font-weight: 600;
  margin-bottom: 8px;
  line-height: 1.3;
}

.servico-preco {
  font-size: 16px;
  font-weight: 700;
  color: #55165e;
}

.servico-card.selected .servico-preco {
  color: #fff;
}

/* Lista de Serviços Selecionados */
.servicos-selecionados {
  margin-top: 30px;
  padding: 20px;
  background: #f8f9fa;
  border-radius: 10px;
}

.servicos-selecionados h3 {
  font-size: 18px;
  color: #55165e;
  margin-bottom: 15px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.lista-servicos {
  margin-bottom: 15px;
}

.servico-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 15px;
  background: white;
  border-radius: 8px;
  margin-bottom: 8px;
  border: 1px solid #e0e0e0;
}

.servico-item span:first-child {
  flex: 1;
  font-weight: 500;
}

.servico-item .preco {
  color: #55165e;
  font-weight: 600;
  margin-right: 10px;
}

.btn-remove {
  background: none;
  border: none;
  color: #dc3545;
  cursor: pointer;
  font-size: 18px;
  padding: 0;
  transition: transform 0.2s;
}

.btn-remove:hover {
  transform: scale(1.2);
}

.valor-total {
  display: flex;
  justify-content: space-between;
  padding: 15px;
  background: white;
  border-radius: 8px;
  font-size: 18px;
  border: 2px solid #55165e;
}

.valor-total .total {
  color: #55165e;
  font-size: 22px;
  font-weight: 700;
}

/* Resumo de Serviços */
.servicos-resumo {
  margin-top: 5px;
}

.servico-resumo-item {
  font-size: 14px;
  color: #666;
  padding: 4px 0;
  border-bottom: 1px dashed #e0e0e0;
}

.servico-resumo-item:last-child {
  border-bottom: none;
}

/* Horários Ocupados e Disponíveis */
.horarios-ocupados-aviso {
  margin-top: 15px;
  padding: 15px;
  background: #fff3cd;
  border: 1px solid #ffc107;
  border-radius: 8px;
  color: #856404;
}

.horarios-ocupados-aviso i {
  margin-right: 8px;
}

.horarios-ocupados-aviso strong {
  display: block;
  margin-bottom: 10px;
}

.horarios-disponiveis {
  margin-top: 15px;
  padding: 15px;
  background: #d4edda;
  border: 1px solid #28a745;
  border-radius: 8px;
  color: #155724;
}

.horarios-disponiveis strong {
  display: block;
  margin-bottom: 10px;
}

.horarios-lista {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-top: 8px;
}

.horario-ocupado {
  background: #dc3545;
  color: white;
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 500;
}

.btn-horario {
  background: white;
  border: 2px solid #28a745;
  color: #28a745;
  padding: 8px 16px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-horario:hover {
  background: #28a745;
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
}

.btn-horario.selected {
  background: #55165e;
  border-color: #55165e;
  color: white;
}

/* Responsivo */
@media (max-width: 768px) {
  .servicos-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
  }

  .servico-card {
    padding: 15px 10px;
  }

  .servico-icon {
    font-size: 32px;
  }

  .servico-nome {
    font-size: 12px;
  }

  .servico-preco {
    font-size: 14px;
  }

  .form-row {
    grid-template-columns: 1fr;
  }

  .progress-steps {
    padding: 20px 10px;
  }

  .step-line {
    width: 50px;
  }

  .step span {
    font-size: 12px;
  }

  .form-container {
    padding: 0 15px 30px 15px;
  }

  .card-body {
    padding: 20px;
  }

  .page-header {
    padding: 20px;
  }

  .form-actions {
    flex-direction: column;
  }

  .btn-next,
  .btn-secondary,
  .btn-submit {
    width: 100%;
    justify-content: center;
  }
}
</style>

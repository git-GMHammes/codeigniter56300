// ------------------------
// IMPORTS - Dependências necessárias para o funcionamento do widget
// ------------------------

// Flutter core - Framework base
import 'package:flutter/material.dart';

// Camada de Application - Controller que gerencia o fluxo de registro
import '../../application/auth_controller.dart';

// Camada de Domain - Caso de uso de registro (regras de negócio)
import '../../domain/usecases/register_user.dart';

// Camada de Data - Implementação do repositório que acessa dados
import '../../data/repositories/auth_repository_impl.dart';

// Camada de Data - Data Source que faz as requisições HTTP
import '../../data/datasources/auth_remote_ds.dart';

// Core - Configurações de ambiente (URLs, chaves, etc)
import '../../../../core/config/env.dart';

// Biblioteca HTTP para comunicação com API
import 'package:dio/dio.dart';

// ------------------------
// WIDGET STATEFUL - Widget com estado mutável para formulário de registro
// ------------------------
// StatefulWidget permite que o widget tenha estado que pode mudar ao longo do tempo
// Necessário para formulários que capturam entrada do usuário
class RegisterCard extends StatefulWidget {
  const RegisterCard({super.key});

  @override
  State<RegisterCard> createState() => _RegisterCardState();
}

// ------------------------
// STATE CLASS - Gerencia o estado e lógica do RegisterCard
// ------------------------
class _RegisterCardState extends State<RegisterCard> {
  // ------------------------
  // CONTROLADORES - Gerenciam o estado dos campos de texto
  // ------------------------

  // Etapa 1 - Chave global do formulário para validação
  // Permite validar todos os campos do formulário de uma vez
  final _formKey = GlobalKey<FormState>();

  // Etapa 2 - Controladores de texto para capturar entrada do usuário
  // Cada TextEditingController gerencia o texto de um campo
  final _userController = TextEditingController(); // Campo de usuário
  final _passwordController = TextEditingController(); // Campo de senha
  final _confirmController = TextEditingController(); // Campo de confirmação

  // ------------------------
  // INJEÇÃO DE DEPENDÊNCIAS - Configuração da arquitetura limpa
  // ------------------------

  // Etapa 3 - Inicialização do controller com todas as camadas
  // Segue o padrão Clean Architecture: Controller -> UseCase -> Repository -> DataSource
  late final controller = AuthController(
    RegisterUser(
      // UseCase: regras de negócio
      AuthRepositoryImpl(
        // Repository: abstração de dados
        AuthRemoteDataSource(
          // DataSource: comunicação HTTP
          dio: Dio(
            // Cliente HTTP configurado
            BaseOptions(baseUrl: Env.baseUrl), // URL base da API
          ),
        ),
      ),
    ),
  );

  // ------------------------
  // ESTADO DE UI - Variáveis que controlam o comportamento visual
  // ------------------------

  // Etapa 4 - Flags de controle de estado
  bool _isLoading = false; // Indica se está processando o cadastro
  String? _errorMessage; // Mensagem de erro para exibir ao usuário
  String? _successMessage; // Mensagem de sucesso após cadastro

  // ------------------------
  // GERENCIAMENTO DE FOCO - Melhora a experiência do usuário
  // ------------------------

  // Etapa 5 - FocusNodes para controlar a navegação entre campos
  // Permite avançar para o próximo campo ao pressionar "Enter"
  final _userFocus = FocusNode(); // Foco do campo usuário
  final _passwordFocus = FocusNode(); // Foco do campo senha
  final _confirmFocus = FocusNode(); // Foco do campo confirmação

  // ------------------------
  // DISPOSE - Libera recursos quando o widget é destruído
  // ------------------------

  @override
  void dispose() {
    // Etapa 1 - Dispose dos controllers de texto
    // Importante para evitar memory leaks
    _userController.dispose();
    _passwordController.dispose();
    _confirmController.dispose();

    // Etapa 2 - Dispose dos FocusNodes
    _userFocus.dispose();
    _passwordFocus.dispose();
    _confirmFocus.dispose();

    // Etapa 3 - Chama o dispose da classe pai
    super.dispose();
  }

  // ------------------------
  // BUILD METHOD - Constrói a interface visual do widget
  // ------------------------

  @override
  Widget build(BuildContext context) {
    // ------------------------
    // CONFIGURAÇÃO DE TEMA - Define estilos visuais reutilizáveis
    // ------------------------

    // Etapa 1 - Obtém o tema atual do app
    final theme = Theme.of(context);

    // Etapa 2 - Define bordas padrão para os campos
    final border = OutlineInputBorder(
      borderRadius: BorderRadius.circular(12),
      borderSide: BorderSide(
        color: theme.colorScheme.primary.withValues(alpha: 0.2),
      ),
    );

    // Etapa 3 - Define bordas para estado de erro
    final errorBorder = OutlineInputBorder(
      borderRadius: BorderRadius.circular(12),
      borderSide: BorderSide(color: Colors.red.shade300),
    );

    // ------------------------
    // ESTRUTURA PRINCIPAL - Layout do card de registro
    // ------------------------

    return Center(
      child: Card(
        // Etapa 1 - Configuração visual do card
        elevation: 4, // Sombra
        shape: RoundedRectangleBorder(
          // Bordas arredondadas
          borderRadius: BorderRadius.circular(18),
        ),
        margin: const EdgeInsets.symmetric(
          horizontal: 24,
          vertical: 32,
        ), // Margens

        child: Padding(
          padding: const EdgeInsets.all(24.0), // Espaçamento interno

          child: SizedBox(
            width: 380, // Largura fixa do formulário
            // ------------------------
            // FORMULÁRIO - Estrutura de validação e campos
            // ------------------------
            child: Form(
              key: _formKey, // Chave para validação

              child: Column(
                mainAxisSize: MainAxisSize.min, // Altura mínima necessária
                crossAxisAlignment:
                    CrossAxisAlignment.stretch, // Campos ocupam largura total

                children: [
                  // ------------------------
                  // CAMPO USUÁRIO - Primeiro campo do formulário
                  // ------------------------

                  // Etapa 1 - Label do campo
                  const Text(
                    "Usuário",
                    style: TextStyle(fontWeight: FontWeight.w600, fontSize: 17),
                  ),
                  const SizedBox(height: 8),

                  // Etapa 2 - Campo de entrada
                  TextFormField(
                    controller:
                        _userController, // Controller que gerencia o texto
                    focusNode: _userFocus, // Node de foco
                    textInputAction:
                        TextInputAction.next, // Ação do teclado: próximo campo
                    // Configuração visual
                    decoration: InputDecoration(
                      border: border,
                      enabledBorder: border,
                      focusedBorder: border,
                      errorBorder: errorBorder,
                      hintText: "Escolha um nome de usuário",
                      prefixIcon: const Icon(Icons.person_2_rounded),
                    ),

                    // Etapa 3 - Validação do campo
                    validator: (v) {
                      if (v == null || v.trim().isEmpty) {
                        return "Preencha o usuário";
                      }
                      if (v.length < 6) return "Mínimo 6 caracteres";
                      if (v.contains(" ")) return "Não use espaços";
                      return null; // null = validação OK
                    },

                    // Etapa 4 - Navegação ao pressionar Enter
                    onFieldSubmitted:
                        (_) =>
                            FocusScope.of(context).requestFocus(_passwordFocus),
                  ),

                  const SizedBox(height: 16),

                  // ------------------------
                  // CAMPO SENHA - Segundo campo do formulário
                  // ------------------------

                  // Etapa 1 - Label do campo
                  const Text(
                    "Senha",
                    style: TextStyle(fontWeight: FontWeight.w600, fontSize: 17),
                  ),
                  const SizedBox(height: 8),

                  // Etapa 2 - Campo de entrada
                  TextFormField(
                    controller: _passwordController,
                    focusNode: _passwordFocus,
                    textInputAction: TextInputAction.next,
                    obscureText: true, // Oculta o texto (senha)

                    decoration: InputDecoration(
                      border: border,
                      enabledBorder: border,
                      focusedBorder: border,
                      errorBorder: errorBorder,
                      hintText: "Digite sua senha",
                      prefixIcon: const Icon(Icons.lock_outline_rounded),
                    ),

                    // Etapa 3 - Validação
                    validator: (v) {
                      if (v == null || v.trim().isEmpty) {
                        return "Preencha a senha";
                      }
                      if (v.length < 8) return "Mínimo 8 caracteres";
                      return null;
                    },

                    // Etapa 4 - Navegação
                    onFieldSubmitted:
                        (_) =>
                            FocusScope.of(context).requestFocus(_confirmFocus),
                  ),

                  const SizedBox(height: 16),

                  // ------------------------
                  // CAMPO CONFIRMAR SENHA - Terceiro campo do formulário
                  // ------------------------

                  // Etapa 1 - Label
                  const Text(
                    "Confirmar Senha",
                    style: TextStyle(fontWeight: FontWeight.w600, fontSize: 17),
                  ),
                  const SizedBox(height: 8),

                  // Etapa 2 - Campo de entrada
                  TextFormField(
                    controller: _confirmController,
                    focusNode: _confirmFocus,
                    textInputAction:
                        TextInputAction.done, // Última campo: "concluir"
                    obscureText: true,

                    decoration: InputDecoration(
                      border: border,
                      enabledBorder: border,
                      focusedBorder: border,
                      errorBorder: errorBorder,
                      hintText: "Confirme sua senha",
                      prefixIcon: const Icon(Icons.lock_outline_rounded),
                    ),

                    // Etapa 3 - Validação: verifica se senhas coincidem
                    validator: (v) {
                      if (v == null || v.trim().isEmpty) {
                        return "Confirme a senha";
                      }
                      if (v != _passwordController.text) {
                        return "Senhas não conferem";
                      }
                      return null;
                    },

                    // Etapa 4 - Submissão ao pressionar Enter
                    onFieldSubmitted: (_) => _onCadastrar(),
                  ),

                  const SizedBox(height: 24),

                  // ------------------------
                  // MENSAGENS DE FEEDBACK - Exibe erros ou sucesso
                  // ------------------------

                  // Etapa 1 - Mensagem de erro
                  if (_errorMessage != null)
                    Padding(
                      padding: const EdgeInsets.only(bottom: 8),
                      child: Text(
                        _errorMessage!,
                        style: const TextStyle(color: Colors.red),
                      ),
                    ),

                  // Etapa 2 - Mensagem de sucesso
                  if (_successMessage != null)
                    Padding(
                      padding: const EdgeInsets.only(bottom: 8),
                      child: Text(
                        _successMessage!,
                        style: const TextStyle(color: Colors.green),
                      ),
                    ),

                  // ------------------------
                  // BOTÃO DE CADASTRO - Submissão do formulário
                  // ------------------------
                  ElevatedButton(
                    // Etapa 1 - Desabilita durante loading
                    onPressed: _isLoading ? null : _onCadastrar,

                    // Etapa 2 - Estilo do botão
                    style: ElevatedButton.styleFrom(
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(18),
                      ),
                      padding: const EdgeInsets.symmetric(vertical: 14),
                      backgroundColor: theme.colorScheme.primary,
                    ),

                    // Etapa 3 - Conteúdo: loading ou texto
                    child:
                        _isLoading
                            ? const SizedBox(
                              width: 18,
                              height: 18,
                              child: CircularProgressIndicator(
                                strokeWidth: 2,
                                valueColor: AlwaysStoppedAnimation<Color>(
                                  Colors.white,
                                ),
                              ),
                            )
                            : const Text(
                              "Cadastrar",
                              style: TextStyle(
                                fontSize: 17,
                                color: Colors.white,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }

  // ------------------------
  // MÉTODO DE CADASTRO - Processa o registro do usuário
  // ------------------------

  Future<void> _onCadastrar() async {
    // Etapa 1 - Fecha o teclado virtual
    FocusScope.of(context).unfocus();

    // Etapa 2 - Limpa mensagens anteriores
    setState(() {
      _errorMessage = null;
      _successMessage = null;
    });

    // Etapa 3 - Valida o formulário
    // Se algum campo estiver inválido, para a execução
    if (!_formKey.currentState!.validate()) return;

    // Etapa 4 - Ativa estado de loading
    setState(() => _isLoading = true);

    // Etapa 5 - Chama o controller para registrar o usuário
    // O controller retorna null se sucesso, ou uma mensagem de erro
    final erro = await controller.registrar(
      _userController.text.trim(), // Remove espaços extras
      _passwordController.text.trim(),
      _confirmController.text.trim(),
    );

    // Etapa 6 - Desativa estado de loading
    setState(() => _isLoading = false);

    // Etapa 7 - Processa o resultado
    if (erro == null) {
      // Sucesso: exibe mensagem e fecha a tela
      setState(() => _successMessage = 'Usuário cadastrado com sucesso!');
      await Future.delayed(const Duration(seconds: 1)); // Aguarda 1 segundo
      if (mounted) Navigator.of(context).pop(); // Fecha a tela
    } else {
      // Erro: exibe mensagem de erro
      setState(() => _errorMessage = erro);
    }
  }
}

// ------------------------
// RESUMO DA ARQUITETURA
// ------------------------
/*
Este widget implementa Clean Architecture com as seguintes camadas:

1. PRESENTATION (este arquivo)
   - Widget UI com formulário de registro
   - Gerencia estado local (loading, mensagens)
   
2. APPLICATION (controller)
   - Orquestra o fluxo entre UI e domain
   - Recebe dados da UI e chama o use case
   
3. DOMAIN (use case)
   - Contém regras de negócio
   - Interface agnóstica de framework
   
4. DATA (repository + datasource)
   - Repository: abstração de acesso a dados
   - DataSource: implementação HTTP real com Dio

Fluxo de dados:
UI → Controller → UseCase → Repository → DataSource → API
API → DataSource → Repository → UseCase → Controller → UI

Vantagens:
- Testabilidade: cada camada pode ser testada isoladamente
- Manutenibilidade: mudanças em uma camada não afetam outras
- Escalabilidade: fácil adicionar novos recursos
- Desacoplamento: dependências apontam para abstrações
*/

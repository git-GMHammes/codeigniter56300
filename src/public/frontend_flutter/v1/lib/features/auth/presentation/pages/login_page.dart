import 'package:flutter/material.dart';
import '../controllers/auth_controller.dart';
import '../../data/datasources/auth_remote_datasource.dart';
import '../../data/repositories/auth_repository_impl.dart';
import 'register_page.dart';

// componentes
import '../../../../shared/widgets/form_card.dart';
import '../../../../shared/widgets/labeled_text_field.dart';
import '../../../../shared/widgets/password_field.dart';
import '../../../../shared/widgets/primary_button.dart';
import '../../../../shared/widgets/link_button.dart';

class LoginPage extends StatefulWidget {
  final String baseUrl;
  const LoginPage({super.key, required this.baseUrl});

  @override
  State<LoginPage> createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  late final AuthController controller;

  @override
  void initState() {
    super.initState();
    final remote = AuthRemoteDataSource(baseUrl: widget.baseUrl);
    final repo = AuthRepositoryImpl(remote: remote);
    controller = AuthController(repository: repo);
  }

  @override
  void dispose() {
    controller.disposeAll();
    super.dispose();
  }

  void _showMessageWithMessenger(ScaffoldMessengerState messenger, String msg) {
    messenger.showSnackBar(SnackBar(content: Text(msg)));
  }

  Future<void> _onLogin() async {
    final messenger = ScaffoldMessenger.of(context);
    final navigator = Navigator.of(context);

    if (controller.loginUserController.text.trim().isEmpty ||
        controller.loginPasswordController.text.isEmpty) {
      _showMessageWithMessenger(messenger, 'Preencha usuário e senha');
      return;
    }

    try {
      final resp = await controller.login();

      // checar mounted antes de usar navigator/messenger é redundante pois usamos referências,
      // mas se você quiser evitar executar ações caso o widget seja desmontado:
      if (!mounted) return;

      _showMessageWithMessenger(messenger, 'Login OK: ${resp['status'] ?? ''}');

      // exemplo de navegação (descomente/adapte)
      // navigator.pushReplacementNamed('/home');
    } catch (e) {
      if (!mounted) return;
      _showMessageWithMessenger(messenger, 'Erro ao logar: ${e.toString()}');
    }
  }

  void _openRegister() {
    Navigator.of(context).push(
      MaterialPageRoute(builder: (_) => RegisterPage(baseUrl: widget.baseUrl)),
    );
  }

  @override
  Widget build(BuildContext context) {
    // o FormCard centraliza e define largura máxima para telas largas
    return Scaffold(
      appBar: AppBar(
        leading: IconButton(
          icon: const Icon(Icons.arrow_back),
          onPressed: () => Navigator.of(context).maybePop(),
        ),
        title: const Text('Login'),
        backgroundColor: Theme.of(context).scaffoldBackgroundColor,
        foregroundColor: Colors.black87,
        elevation: 0,
      ),
      body: FormCard(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            LabeledTextField(
              label: 'Usuário',
              controller: controller.loginUserController,
            ),
            const SizedBox(height: 12),
            PasswordField(
              label: 'Senha',
              controller: controller.loginPasswordController,
            ),
            const SizedBox(height: 18),
            PrimaryButton(onPressed: _onLogin, label: 'Entrar'),
            const SizedBox(height: 8),
            LinkButton(onPressed: _openRegister, label: 'Criar conta'),
          ],
        ),
      ),
    );
  }
}

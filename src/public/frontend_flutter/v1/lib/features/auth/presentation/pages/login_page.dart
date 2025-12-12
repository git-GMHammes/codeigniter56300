import 'package:flutter/material.dart';
import '../controllers/auth_controller.dart';
import '../../data/datasources/auth_remote_datasource.dart';
import '../../data/repositories/auth_repository_impl.dart';

class LoginPage extends StatefulWidget {
  // você pode passar o baseUrl via construtor ou ler de env.dart
  final String baseUrl;
  const LoginPage({Key? key, required this.baseUrl}) : super(key: key);

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

  void _showMessage(String msg) {
    ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(msg)));
  }

  Future<void> _onLogin() async {
    if (controller.loginUserController.text.trim().isEmpty ||
        controller.loginPasswordController.text.isEmpty) {
      _showMessage('Preencha usuário e senha');
      return;
    }
    try {
      final resp = await controller.login();
      // aqui você decide o que fazer: salvar token, navegar...
      _showMessage('Login OK: ${resp['status'] ?? ''}');
    } catch (e) {
      _showMessage('Erro ao logar: ${e.toString()}');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Login')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            TextField(
              controller: controller.loginUserController,
              decoration: const InputDecoration(labelText: 'Usuário'),
            ),
            const SizedBox(height: 8),
            TextField(
              controller: controller.loginPasswordController,
              obscureText: true,
              decoration: const InputDecoration(labelText: 'Senha'),
            ),
            const SizedBox(height: 16),
            ElevatedButton(onPressed: _onLogin, child: const Text('Entrar')),
            TextButton(
              onPressed: () {
                Navigator.of(context).push(
                  MaterialPageRoute(
                    builder: (_) => RegisterPage(baseUrl: widget.baseUrl),
                  ),
                );
              },
              child: const Text('Criar conta'),
            ),
          ],
        ),
      ),
    );
  }
}

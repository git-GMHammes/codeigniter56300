import 'package:flutter/material.dart';
import 'package:dio/dio.dart';
import '../../../../features/home/presentation/pages/home_page.dart';
import '../../application/auth_controller.dart';
import '../../domain/usecases/login_user.dart';
import '../../data/repositories/auth_repository_impl.dart';
import '../../data/datasources/auth_remote_ds.dart';
import '../../../../core/config/env.dart';
import '../widgets/login_card.dart';

class LoginPage extends StatefulWidget {
  const LoginPage({super.key});

  @override
  State<LoginPage> createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  AuthController _buildAuthController() {
    return AuthController(
      loginUserUseCase: LoginUser(
        authRepository: AuthRepositoryImpl(
          remoteDataSource: AuthRemoteDataSource(
            dio: Dio(
              BaseOptions(
                baseUrl: Env.baseUrl,
                headers: {'Content-Type': 'application/json'},
              ),
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildContent(BuildContext context) {
    final authController = _buildAuthController();

    return LoginCard(
      onSubmit: (user, password) async {
        await authController.login(user, password);

        if (authController.authUser != null) {
          if (!mounted) return;

          // Mensagem de sucesso
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Row(
                children: const [
                  Icon(Icons.check_circle, color: Colors.white),
                  SizedBox(width: 8),
                  Text('Login realizado com sucesso!'),
                ],
              ),
              backgroundColor: Colors.green,
            ),
          );

          // Redirecionar para HomePage
          Navigator.pushReplacement(
            context,
            MaterialPageRoute(builder: (_) => const HomePage()),
          );
        } else {
          if (!mounted) return;

          // Mensagem de erro
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Row(
                children: const [
                  Icon(Icons.error, color: Colors.white),
                  SizedBox(width: 8),
                  Text('Erro ao realizar login'),
                ],
              ),
              backgroundColor: Colors.red,
            ),
          );
        }
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: SafeArea(child: _buildContent(context)),
    );
  }
}

import 'package:flutter/material.dart';
import '../widgets/login_head.dart';
import '../widgets/login_card.dart';
import '../widgets/login_footer.dart';

class LoginPage extends StatelessWidget {
  const LoginPage({super.key});

  @override
  Widget build(BuildContext context) {
    // Detecta tema para cores adaptativas
    final theme = Theme.of(context);
    final isDark = theme.brightness == Brightness.dark;
    final background = theme.scaffoldBackgroundColor;

    return Scaffold(
      backgroundColor: background,
      body: SafeArea(
        child: Column(
          children: [
            // Head com <- Voltar | Nome da Tela atual
            const LoginHead(title: 'Login de acesso'),
            // Conteúdo central
            Expanded(
              child: SingleChildScrollView(
                padding: const EdgeInsets.symmetric(
                  horizontal: 20,
                  vertical: 18,
                ),
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    // Ícone de cadeado acima do quadro
                    SizedBox(height: 8),
                    Icon(
                      Icons.lock_outline,
                      size: 64,
                      color:
                          isDark
                              ? Colors.purpleAccent.shade100
                              : Colors.indigo.shade900,
                    ),
                    const SizedBox(height: 14),
                    // Card 3D com formulário
                    const LoginCard(),
                    const SizedBox(height: 18),
                    // Texto de ajuda/links destacado logo abaixo do card
                    Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        TextButton(
                          onPressed: () {},
                          child: const Text('Cadastre-se'),
                        ),
                        const SizedBox(width: 12),
                        TextButton(
                          onPressed: () {},
                          child: const Text('Esqueci minha senha'),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ),
            // Footer com menu/ícones (somente os botões relevantes para a tela)
            const LoginFooter(),
          ],
        ),
      ),
    );
  }
}

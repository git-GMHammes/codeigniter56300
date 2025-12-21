import 'package:flutter/material.dart';
import '../widgets/login_head.dart';
import '../widgets/login_footer.dart';
import '../widgets/register_card.dart';

/// Página de cadastro de novo usuário
class RegisterPage extends StatelessWidget {
  const RegisterPage({super.key});

  // ══════════════════════════════════════════════════════════════════════════
  // BUILD PRINCIPAL
  // ══════════════════════════════════════════════════════════════════════════
  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return Scaffold(
      backgroundColor: theme.scaffoldBackgroundColor,
      body: SafeArea(
        child: Column(
          children: [
            // ─────────────────────────────────────────────────────────────────
            // HEADER
            // ─────────────────────────────────────────────────────────────────
            const LoginHead(title: 'Cadastro'),

            // ─────────────────────────────────────────────────────────────────
            // CONTEÚDO CENTRAL - RegisterCard orquestra Step 1 e Step 2
            // ─────────────────────────────────────────────────────────────────
            Expanded(
              child: SingleChildScrollView(
                padding: const EdgeInsets.symmetric(
                  horizontal: 20,
                  vertical: 18,
                ),
                child: const RegisterCard(), // Este widget controla os steps
              ),
            ),

            // ─────────────────────────────────────────────────────────────────
            // FOOTER
            // ─────────────────────────────────────────────────────────────────
            const LoginFooter(),
          ],
        ),
      ),
    );
  }
}

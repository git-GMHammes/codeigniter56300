import 'package:flutter/material.dart';
import '../widgets/login_head.dart';
import '../widgets/login_footer.dart';
import '../widgets/register_card.bkp_002';

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
          children:  [
            // ─────────────────────────────────────────────────────────────────
            // HEADER
            // ─────────────────────────────────────────────────────────────────
            const LoginHead(title: 'Cadastro'),

            // ─────────────────────────────────────────────────────────────────
            // CONTEÚDO CENTRAL
            // ─────────────────────────────────────────────────────────────────
            Expanded(
              child:  SingleChildScrollView(
                padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 18),
                child:  const RegisterCard(),
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
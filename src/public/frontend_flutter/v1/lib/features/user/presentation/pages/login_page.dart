import 'package:flutter/material.dart';
import '../widgets/login_head.dart';
import '../widgets/login_card.dart';
import '../widgets/login_footer.dart';
import 'register_page.dart';

class LoginPage extends StatelessWidget {
  const LoginPage({super.key});

  // ══════════════════════════════════════════════════════════════════════════
  // NAVIGATION
  // ══════════════════════════════════════════════════════════════════════════

  /// Navega para a página de cadastro
  void _navigateToRegister(BuildContext context) {
    Navigator.push(
      context,
      MaterialPageRoute(builder: (_) => const RegisterPage()),
    );
  }

  /// Navega para a página de recuperação de senha
  void _navigateToForgotPassword(BuildContext context) {
    // TODO: Implementar navegação para recuperação de senha
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(
        content: Text('Funcionalidade em desenvolvimento'),
        duration: Duration(seconds: 2),
      ),
    );
  }

  // ══════════════════════════════════════════════════════════════════════════
  // BUILDERS
  // ══════════════════════════════════════════════════════════════════════════

  /// Links:  Cadastre-se e Esqueci minha senha
  Widget _buildActionLinks(BuildContext context, ThemeData theme, bool isDark) {
    final linkColor =
        isDark ? Colors.purpleAccent.shade100 : Colors.indigo.shade700;

    return Row(
      mainAxisAlignment: MainAxisAlignment.center,
      children: [
        TextButton(
          onPressed: () => _navigateToRegister(context),
          child: Text('Cadastre-se', style: TextStyle(color: linkColor)),
        ),
        const SizedBox(width: 12),
        TextButton(
          onPressed: () => _navigateToForgotPassword(context),
          child: Text(
            'Esqueci minha senha',
            style: TextStyle(color: linkColor),
          ),
        ),
      ],
    );
  }

  /// Conteúdo principal scrollável
  Widget _buildContent(BuildContext context, ThemeData theme, bool isDark) {
    return SingleChildScrollView(
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 18),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          // ─────────────────────────────────────────────────────────────────
          // ESPAÇAMENTO SUPERIOR
          // ─────────────────────────────────────────────────────────────────
          const SizedBox(height: 8),

          // ─────────────────────────────────────────────────────────────────
          // CARD DE LOGIN (já contém o ícone do cadeado)
          // ─────────────────────────────────────────────────────────────────
          const LoginCard(),
          const SizedBox(height: 18),

          // ─────────────────────────────────────────────────────────────────
          // LINKS DE AÇÃO
          // ─────────────────────────────────────────────────────────────────
          _buildActionLinks(context, theme, isDark),
        ],
      ),
    );
  }

  // ══════════════════════════════════════════════════════════════════════════
  // BUILD PRINCIPAL
  // ══════════════════════════════════════════════════════════════════════════
  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final isDark = theme.brightness == Brightness.dark;

    return Scaffold(
      backgroundColor: theme.scaffoldBackgroundColor,
      body: SafeArea(
        child: Column(
          children: [
            // ─────────────────────────────────────────────────────────────────
            // HEADER
            // ─────────────────────────────────────────────────────────────────
            const LoginHead(title: 'Login de acesso'),

            // ─────────────────────────────────────────────────────────────────
            // CONTEÚDO CENTRAL
            // ─────────────────────────────────────────────────────────────────
            Expanded(child: _buildContent(context, theme, isDark)),

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

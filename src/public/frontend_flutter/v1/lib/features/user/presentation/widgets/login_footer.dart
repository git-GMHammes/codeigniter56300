import 'package:flutter/material.dart';
import '../pages/register_page.dart';

class LoginFooter extends StatelessWidget {
  const LoginFooter({super.key});

  void _navigateToRegister(BuildContext context) {
    Navigator.push(
      context,
      MaterialPageRoute(builder: (context) => RegisterPage()),
    );
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final isDark = theme.brightness == Brightness.dark;
    // bottomAppBarColor não existe em ThemeData atual -> usar bottomNavigationBarTheme ou colorScheme
    final bgColor =
        theme.bottomNavigationBarTheme.backgroundColor ??
        theme.colorScheme.surface;

    return Container(
      color: bgColor,
      padding: const EdgeInsets.symmetric(vertical: 8, horizontal: 12),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceEvenly,
        children: [
          // Apenas os ícones relevantes para a tela de Login (Cadastrar, Esqueci)
          _FooterButton(
            icon: Icons.person_add_alt_1,
            label: 'Cadastrar',
            onTap: () => _navigateToRegister(context),
            isDark: isDark,
          ),
          _FooterButton(
            icon: Icons.lock_open,
            label: 'Esqueci',
            onTap: () {},
            isDark: isDark,
          ),
        ],
      ),
    );
  }
}

class _FooterButton extends StatelessWidget {
  final IconData icon;
  final String label;
  final VoidCallback onTap;
  final bool isDark;

  const _FooterButton({
    required this.icon,
    required this.label,
    required this.onTap,
    required this.isDark,
  });

  @override
  Widget build(BuildContext context) {
    final color = isDark ? Colors.white : Colors.black87;
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(10),
      child: Padding(
        padding: const EdgeInsets.symmetric(vertical: 6, horizontal: 10),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(icon, color: color),
            const SizedBox(height: 4),
            Text(label, style: TextStyle(color: color, fontSize: 12)),
          ],
        ),
      ),
    );
  }
}

import 'package:flutter/material.dart';

/// Card de navegação reutilizável para qualquer módulo
class NavCard extends StatelessWidget {
  final IconData icon;
  final String title;
  final String subtitle;
  final String navigateTo;
  final VoidCallback? onTap;

  const NavCard({
    super.key,
    required this.icon,
    required this.title,
    required this.subtitle,
    required this.navigateTo,
    this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final isDark = theme.brightness == Brightness.dark;

    return Material(
      color: theme.cardColor,
      elevation: 6,
      shadowColor:
          isDark
              ? Colors.black.withAlpha(150)
              : const Color.fromRGBO(100, 100, 100, 0.25),
      borderRadius: BorderRadius.circular(14),
      child: InkWell(
        borderRadius: BorderRadius.circular(14),
        onTap: onTap ?? () => Navigator.of(context).pushNamed(navigateTo),
        child: Center(
          child: FittedBox(
            fit: BoxFit.scaleDown,
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Icon(
                  icon,
                  size: 52,
                  color:
                      isDark
                          ? Colors.purpleAccent.shade100
                          : Colors.indigo.shade700,
                ),
                const SizedBox(height: 8),
                Text(
                  title,
                  style: theme.textTheme.titleMedium?.copyWith(
                    fontWeight: FontWeight.w600,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  subtitle,
                  style: theme.textTheme.bodySmall?.copyWith(
                    color: theme.textTheme.bodySmall?.color?.withAlpha(178),
                    fontSize: 11,
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

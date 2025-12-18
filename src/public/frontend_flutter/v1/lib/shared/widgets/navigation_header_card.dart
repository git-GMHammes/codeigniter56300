import 'package:flutter/material.dart';

/// Widget de cabeçalho reutilizável entre módulos
/// Pode ser usado como "Home" ou como "Voltar" dependendo do contexto
class NavigationHeaderCard extends StatelessWidget {
  final IconData icon;
  final String title;
  final String subtitle;
  final String? navigateTo;
  final VoidCallback? onTap;

  const NavigationHeaderCard({
    super.key,
    this.icon = Icons.home_outlined,
    this.title = 'Início',
    this.subtitle = 'Painel principal',
    this.navigateTo,
    this.onTap,
  });

  /// Factory para botão de voltar à Home
  const NavigationHeaderCard.backToHome({
    super.key,
    this.icon = Icons.home_outlined,
    this.title = 'Início',
    this.subtitle = 'Voltar ao painel',
    this.navigateTo = '/home',
    this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final isDark = theme.brightness == Brightness.dark;

    return Material(
      color: theme.cardColor,
      elevation: 8,
      shadowColor:
          isDark
              ? Colors.black.withAlpha(150)
              : const Color.fromRGBO(100, 100, 100, 0.25),
      borderRadius: BorderRadius.circular(16),
      child: InkWell(
        borderRadius: BorderRadius.circular(16),
        onTap: () {
          if (onTap != null) {
            onTap!();
          } else if (navigateTo != null) {
            Navigator.of(context).pushNamed(navigateTo!);
          }
        },
        child: Padding(
          padding: const EdgeInsets.all(18.0),
          child: Center(
            child: FittedBox(
              fit: BoxFit.scaleDown,
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Icon(
                    icon,
                    size: 64,
                    color:
                        isDark
                            ? Colors.purpleAccent.shade100
                            : Colors.indigo.shade700,
                  ),
                  const SizedBox(height: 8),
                  Text(
                    title,
                    style: theme.textTheme.titleLarge?.copyWith(
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    subtitle,
                    style: theme.textTheme.bodySmall?.copyWith(
                      color: theme.textTheme.bodySmall?.color?.withAlpha(178),
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
}

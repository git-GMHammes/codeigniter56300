import 'package:flutter/material.dart';

/// Widget da "casinha" - Card de cabeçalho da Home
class HomeHeaderCard extends StatelessWidget {
  const HomeHeaderCard({super.key});

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
          // TODO: ação ao clicar
        },
        child: Padding(
          padding: const EdgeInsets.all(18.0),
          child: Center(
            child: FittedBox(
              fit: BoxFit.scaleDown,
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  // Ícone da casinha
                  Icon(
                    Icons.home_outlined,
                    size: 64,
                    color:
                        isDark
                            ? Colors.purpleAccent.shade100
                            : Colors.indigo.shade700,
                  ),
                  const SizedBox(height: 8),
                  // Título
                  Text(
                    'Início',
                    style: theme.textTheme.titleLarge?.copyWith(
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                  const SizedBox(height: 4),
                  // Subtítulo
                  Text(
                    'Painel principal',
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

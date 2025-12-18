import 'package:flutter/material.dart';

class HomeProjectCard extends StatelessWidget {
  const HomeProjectCard({super.key});

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
        onTap: () {
          Navigator.of(context).pushNamed('/project');
        },
        child: Center(
          child: FittedBox(
            fit: BoxFit.scaleDown,
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Icon(
                  Icons.build_circle_outlined,
                  size: 52,
                  color:
                      isDark
                          ? Colors.purpleAccent.shade100
                          : Colors.indigo.shade700,
                ),
                const SizedBox(height: 8),
                Text(
                  'Projetos',
                  style: theme.textTheme.titleMedium?.copyWith(
                    fontWeight: FontWeight.w600,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  'O que oferecemos',
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

import 'package:flutter/material.dart';

class HomeFaleCard extends StatelessWidget {
  const HomeFaleCard({super.key});

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
        onTap:  () {
          Navigator.of(context).pushNamed('/contact');
        },
        child: Center(
          child: FittedBox(
            fit: BoxFit.scaleDown,
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Icon(
                  Icons.chat_bubble_outline,
                  size: 52,
                  color:
                      isDark
                          ? Colors.purpleAccent.shade100
                          : Colors.indigo.shade700,
                ),
                const SizedBox(height: 8),
                Text(
                  'Fale Conosco',
                  style: theme.textTheme.titleMedium?.copyWith(
                    fontWeight: FontWeight.w600,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  'Contato e suporte',
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

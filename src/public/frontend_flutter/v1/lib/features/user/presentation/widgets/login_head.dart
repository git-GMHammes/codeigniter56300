import 'package:flutter/material.dart';

class LoginHead extends StatelessWidget {
  final String title;
  const LoginHead({super.key, required this.title});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final isDark = theme.brightness == Brightness.dark;
    final bg =
        theme.appBarTheme.backgroundColor ??
        (isDark ? Colors.grey[900] : Colors.white);
    final iconColor = isDark ? Colors.white : Colors.black87;

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 6),
      color: bg,
      child: Row(
        children: [
          IconButton(
            icon: Icon(Icons.arrow_back, color: iconColor),
            onPressed: () => Navigator.of(context).maybePop(),
          ),
          Expanded(
            child: Text(
              title,
              style: theme.textTheme.titleMedium?.copyWith(color: iconColor),
            ),
          ),
          // Espaço para ícones (opcional)
          Icon(Icons.more_vert, color: iconColor),
          const SizedBox(width: 6),
        ],
      ),
    );
  }
}

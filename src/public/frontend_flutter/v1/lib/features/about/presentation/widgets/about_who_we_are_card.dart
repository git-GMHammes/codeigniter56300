// frontend_flutter/v1/lib/features/about/presentation/widgets/about_quem_somos_card.dart
import 'package:flutter/material.dart';

class AboutQuemSomosCard extends StatelessWidget {
  const AboutQuemSomosCard({super.key});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return Card(
      elevation: 2,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(20.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Icon(
                  Icons.groups_outlined,
                  color: theme.colorScheme.primary,
                  size: 28,
                ),
                const SizedBox(width: 12),
                Text(
                  'Quem Somos',
                  style: theme.textTheme.titleLarge?.copyWith(
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 16),
            Text(
              'Somos uma equipe dedicada ao desenvolvimento de sistemas modernos, '
              'eficientes e escaláveis. Nosso trabalho é guiado pela busca constante '
              'por inovação, qualidade técnica e soluções que realmente façam '
              'diferença no dia a dia dos nossos clientes.',
              style: theme.textTheme.bodyLarge?.copyWith(
                height: 1.6,
                color: theme.colorScheme.onSurface.withOpacity(0.8),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

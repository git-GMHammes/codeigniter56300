// frontend_flutter/v1/lib/features/about/presentation/widgets/about_values_card. dart
import 'package:flutter/material.dart';

class AboutValuesCard extends StatelessWidget {
  const AboutValuesCard({super.key});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    final values = [
      {
        'icon': Icons.handshake_outlined,
        'title': 'Integridade',
        'desc': 'Agimos com ética e transparência',
      },
      {
        'icon': Icons.lightbulb_outline,
        'title': 'Inovação',
        'desc': 'Buscamos sempre evoluir',
      },
      {
        'icon': Icons.groups_outlined,
        'title': 'Colaboração',
        'desc': 'Trabalhamos juntos pelo sucesso',
      },
      {
        'icon': Icons.star_outline,
        'title': 'Excelência',
        'desc': 'Entregamos qualidade superior',
      },
    ];

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
                  Icons.diamond_outlined,
                  color: theme.colorScheme.primary,
                  size: 28,
                ),
                const SizedBox(width: 12),
                Text(
                  'Nossos Valores',
                  style: theme.textTheme.titleLarge?.copyWith(
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 20),

            // Grid de valores
            GridView.builder(
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                crossAxisCount: 2,
                crossAxisSpacing: 12,
                mainAxisSpacing: 12,
                childAspectRatio: 1.3,
              ),
              itemCount: values.length,
              itemBuilder: (context, index) {
                final value = values[index];
                return _ValueItem(
                  icon: value['icon'] as IconData,
                  title: value['title'] as String,
                  description: value['desc'] as String,
                );
              },
            ),
          ],
        ),
      ),
    );
  }
}

class _ValueItem extends StatelessWidget {
  final IconData icon;
  final String title;
  final String description;

  const _ValueItem({
    required this.icon,
    required this.title,
    required this.description,
  });

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: theme.colorScheme.surfaceVariant.withOpacity(0.5),
        borderRadius: BorderRadius.circular(12),
      ),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(icon, color: theme.colorScheme.primary, size: 32),
          const SizedBox(height: 8),
          Text(
            title,
            style: theme.textTheme.titleSmall?.copyWith(
              fontWeight: FontWeight.bold,
            ),
            textAlign: TextAlign.center,
          ),
          const SizedBox(height: 4),
          Text(
            description,
            style: theme.textTheme.bodySmall?.copyWith(
              color: theme.colorScheme.onSurface.withOpacity(0.7),
            ),
            textAlign: TextAlign.center,
            maxLines: 2,
            overflow: TextOverflow.ellipsis,
          ),
        ],
      ),
    );
  }
}

import 'package:flutter/material.dart';
import '../../../../shared/widgets/nav_card.dart';

class ProjectMecanicaCard extends StatelessWidget {
  const ProjectMecanicaCard({super.key});

  @override
  Widget build(BuildContext context) {
    return const NavCard(
      icon: Icons.build_outlined,
      title: 'Mecânica',
      subtitle: 'Serviços automotivos',
      navigateTo: '/project/mecanica',
    );
  }
}
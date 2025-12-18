import 'package:flutter/material.dart';
import '../../../../shared/widgets/nav_card.dart';

class ProjectVeterinariaCard extends StatelessWidget {
  const ProjectVeterinariaCard({super. key});

  @override
  Widget build(BuildContext context) {
    return const NavCard(
      icon: Icons.pets_outlined,
      title: 'Veterinária',
      subtitle: 'Cuidados animais',
      navigateTo: '/project/veterinaria',
    );
  }
}
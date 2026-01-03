import 'package:flutter/material.dart';
import '../../../../shared/widgets/nav_card.dart';

class ProjectEstoqueCard extends StatelessWidget {
  const ProjectEstoqueCard({super.key});

  @override
  Widget build(BuildContext context) {
    return const NavCard(
      icon: Icons.inventory_2_outlined,
      title: 'Estoque',
      subtitle: 'Controle de itens',
      navigateTo: '/project/estoque',
    );
  }
}
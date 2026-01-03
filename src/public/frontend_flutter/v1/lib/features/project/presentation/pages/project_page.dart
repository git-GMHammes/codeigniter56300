import 'package:flutter/material.dart';
import '../../../../shared/widgets/navigation_header_card.dart';
import '../widgets/project_row2_card.dart';
import '../widgets/project_row3_card.dart';

class ProjectPage extends StatelessWidget {
  const ProjectPage({super. key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(title: const Text('Projetos')),
      body: const Padding(
        padding: EdgeInsets. all(12.0),
        child: SingleChildScrollView(
          child: Column(
            children: [
              // BLOCO: Casinha (volta para Home)
              SizedBox(
                height: 250,
                child:  NavigationHeaderCard.backToHome(),
              ),
              
              SizedBox(height: 12),
              // BLOCO: Linha 1 - Estoque e Tarefas
              SizedBox(height: 150, child: ProjectRow2Card()),
              SizedBox(height:  12),
              // BLOCO:  Linha 2 - Mecânica e Veterinária
              SizedBox(height: 150, child: ProjectRow3Card()),
              SizedBox(height: 12),
              // BLOCO: Propagandas
              SizedBox(
                height: 100,
                child: Center(
                  child: Text('Espaço reservado para propagandas e avisos'),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
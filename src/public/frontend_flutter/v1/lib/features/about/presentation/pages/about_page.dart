import 'package:flutter/material.dart';
import '../widgets/about_header_card.dart';
import '../widgets/about_who_we_are_card.dart';
import '../widgets/about_mission_card.dart';
import '../widgets/about_team_card.dart';
import '../widgets/about_values_card.dart';
import '../widgets/about_contact_card.dart';

class AboutPage extends StatelessWidget {
  const AboutPage({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title:  const Text('Sobre Nós'),
        centerTitle: true,
        elevation: 0,
      ),
      body: const SingleChildScrollView(
        padding: EdgeInsets. all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children:  [
            // Header com logo/imagem e título
            AboutHeaderCard(),
            SizedBox(height:  16),
            
            // Quem Somos
            AboutQuemSomosCard(),
            SizedBox(height: 16),
            
            // Missão da empresa
            AboutMissionCard(),
            SizedBox(height: 16),
            
            // Valores da empresa
            AboutValuesCard(),
            SizedBox(height: 16),
            
            // Equipe
            AboutTeamCard(),
            SizedBox(height: 16),
            
            // Contato/Localização
            AboutContactCard(),
            SizedBox(height:  24),
          ],
        ),
      ),
    );
  }
}
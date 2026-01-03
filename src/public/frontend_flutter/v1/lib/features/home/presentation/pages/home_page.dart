// 1. IMPORTAÇÃO DO FLUTTER
// Importa a biblioteca Material Design (sem espaço após "package:")
import 'package:flutter/material.dart';

// Importa o widget da casinha
import '../../../../shared/widgets/navigation_header_card.dart';
import '../widgets/home_row2_card.dart';
import '../widgets/home_row3_card.dart';

// 2. CLASSE DA PÁGINA
// StatelessWidget = widget que NÃO muda de estado
class HomePage extends StatelessWidget {
  // 3. CONSTRUTOR
  // const = objeto imutável (melhor performance)
  // super.key = identificador único do widget
  const HomePage({super.key});

  // 4. MÉTODO BUILD
  // Chamado pelo Flutter para desenhar a tela
  // Retorna um Widget
  @override
  Widget build(BuildContext context) {
    // 5. SCAFFOLD - estrutura base da página
    return Scaffold(
      // 6. COR DE FUNDO
      backgroundColor: Colors.white,

      // 7. BARRA SUPERIOR
      appBar: AppBar(title: const Text('Início')),

      // 8. CORPO DA PÁGINA
      body: const Padding(
        padding: EdgeInsets.all(12.0),
        child: SingleChildScrollView(
          child: Column(
            children: [
              // BLOCO:  Casinha
              SizedBox(height: 250, child: NavigationHeaderCard.backToHome()),
              SizedBox(height: 12),
              // BLOCO: Linha 2
              SizedBox(height: 150, child: HomeRow2Card()),
              SizedBox(height: 12),
              // BLOCO:  Linha 3
              SizedBox(height: 150, child: HomeRow3Card()),
              SizedBox(height: 12),
              // BLOCO:  Propagandas (altura fixa, sem Expanded)
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

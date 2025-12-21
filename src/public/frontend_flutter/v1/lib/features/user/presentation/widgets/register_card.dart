import 'package:flutter/material.dart';
import 'register_step1_card.dart';
import 'register_step2_card.dart';
import '../../domain/usecases/register_user.dart';
import '../../data/repositories/auth_repository_impl.dart';
import '../../data/datasources/auth_remote_ds.dart';
import '../../../../core/config/env.dart';
import 'package:dio/dio.dart';

/// Orquestrador que gerencia Step 1 e Step 2 do cadastro
class RegisterCard extends StatefulWidget {
  const RegisterCard({super.key});

  @override
  State<RegisterCard> createState() => _RegisterCardState();
}

class _RegisterCardState extends State<RegisterCard> {
  // ══════════════════════════════════════════════════════════════════════════
  // ESTADO
  // ══════════════════════════════════════════════════════════════════════════

  int _currentStep = 1;

  // Dados do Step 1
  String _savedUser = '';
  String _savedPassword = '';
  int? _savedUserId; // ← NOVO! ID retornado da API

  // ══════════════════════════════════════════════════════════════════════════
  // DEPENDÊNCIAS
  // ══════════════════════════════════════════════════════════════════════════

  late final RegisterUser _useCase = RegisterUser(
    AuthRepositoryImpl(
      AuthRemoteDataSource(dio: Dio(BaseOptions(baseUrl: Env.baseUrl))),
    ),
  );

  // ══════════════════════════════════════════════════════════════════════════
  // STEP 1 COMPLETE - Cadastra credenciais e pega o ID
  // ══════════════════════════════════════════════════════════════════════════

  Future<void> _handleStep1Complete(String user, String password) async {
    // ignore: avoid_print
    print('🔐 Step 1: Cadastrando credenciais...');
    // ignore: avoid_print
    print('   User: $user');

    // Mostra loading
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (context) => const Center(child: CircularProgressIndicator()),
    );

    // Chama a API para cadastrar credenciais
    final result = await _useCase(user, password, password);

    // Fecha loading
    if (mounted) Navigator.of(context).pop();

    // Processa resultado
    result.fold(
      // ERRO
      (failure) {
        // ignore: avoid_print
        print('❌ Erro no Step 1: ${failure.message}');
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text('❌ ${failure.message}'),
              backgroundColor: Colors.red,
            ),
          );
        }
      },
      // SUCESSO
      (user) {
        // ignore: avoid_print
        print('✅ Step 1 sucesso!');
        // ignore: avoid_print
        print('   ID retornado: ${user.id}');

        // Salva os dados e o ID
        setState(() {
          _savedUser = _savedUser;
          _savedPassword = password;
          _savedUserId = user.id; // ← SALVA O ID!
          _currentStep = 2; // ← AVANÇA PARA STEP 2
        });
        // ignore: avoid_print
        print('📍 Navegou para Step 2 com userId: ${user.id}');
      },
    );
  }

  // ══════════════════════════════════════════════════════════════════════════
  // STEP 2 BACK
  // ══════════════════════════════════════════════════════════════════════════

  void _handleStep2Back() {
    setState(() => _currentStep = 1);
  }

  // ══════════════════════════════════════════════════════════════════════════
  // STEP 2 COMPLETE - Cadastra dados pessoais
  // ══════════════════════════════════════════════════════════════════════════

  Future<void> _handleStep2Complete({
    required int userId,
    required String name,
    required String cpf,
    required String mail,
    required String phone,
    required String whatsapp,
    required DateTime? dateBirth,
    required String zipCode,
    required String address,
    required String? profileImagePath,
  }) async {
    // ignore: avoid_print
    print('📝 Step 2: Cadastrando dados pessoais...');
    // ignore: avoid_print
    print('   userId: $userId');
    // ignore: avoid_print
    print('   name: $name');

    // Mostra loading
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (context) => const Center(child: CircularProgressIndicator()),
    );

    // Chama a API para cadastrar dados pessoais
    final result = await _useCase.registerCustomer(
      userId: userId,
      name: name,
      cpf: cpf,
      mail: mail,
      phone: phone,
      whatsapp: whatsapp,
      dateBirth: dateBirth,
      zipCode: zipCode,
      address: address,
      profileImagePath: profileImagePath,
    );

    // Fecha loading
    if (mounted) Navigator.of(context).pop();

    // Processa resultado
    result.fold(
      // ERRO
      (failure) {
        // ignore: avoid_print
        print('❌ Erro no Step 2: ${failure.message}');
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text('❌ ${failure.message}'),
              backgroundColor: Colors.red,
            ),
          );
        }
      },
      // SUCESSO
      (_) {
        // ignore: avoid_print
        print('✅ Cadastro completo!');
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(
              content: Text('✅ Cadastro realizado com sucesso!'),
              backgroundColor: Colors.green,
            ),
          );

          // Aguarda e navega
          Future.delayed(const Duration(seconds: 1), () {
            if (mounted) {
              // OPÇÃO 1: Navegar para home
              Navigator.of(context).pushReplacementNamed('/home');

              // OPÇÃO 2: Ou apenas fechar (volta para login)
              // Navigator.of(context).pop();
            }
          });
        }
      },
    );
  }

  // ══════════════════════════════════════════════════════════════════════════
  // BUILD
  // ══════════════════════════════════════════════════════════════════════════

  @override
  Widget build(BuildContext context) {
    if (_currentStep == 1) {
      // STEP 1: Credenciais
      return RegisterStep1Card(
        initialUser: _savedUser,
        initialPassword: _savedPassword,
        onComplete: _handleStep1Complete,
      );
    } else {
      // STEP 2: Dados Pessoais
      // IMPORTANTE: Só renderiza se tiver o userId!
      if (_savedUserId == null) {
        return const Center(child: Text('Erro: userId não encontrado!'));
      }

      return RegisterStep2Card(
        userId: _savedUserId!, // ← PASSA O ID PARA SER VISÍVEL!
        onBack: _handleStep2Back,
        onComplete: _handleStep2Complete,
      );
    }
  }
}

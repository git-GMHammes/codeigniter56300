import 'package:flutter/material.dart';
import '../../domain/repositories/auth_repository.dart';

class AuthController with ChangeNotifier {
  final AuthRepository repository;

  AuthController({required this.repository});

  // login form
  final TextEditingController loginUserController = TextEditingController();
  final TextEditingController loginPasswordController = TextEditingController();

  // register part 1 (credentials)
  final TextEditingController regUserController = TextEditingController();
  final TextEditingController regPasswordController = TextEditingController();
  final TextEditingController regPasswordConfirmController =
      TextEditingController();

  // register part 2 (profile)
  final TextEditingController nameController = TextEditingController();
  final TextEditingController profileController = TextEditingController();
  final TextEditingController phoneController = TextEditingController();
  final TextEditingController dateBirthController = TextEditingController();
  final TextEditingController zipCodeController = TextEditingController();
  final TextEditingController addressController = TextEditingController();
  final TextEditingController cpfController = TextEditingController();
  final TextEditingController whatsappController = TextEditingController();
  final TextEditingController mailController = TextEditingController();

  bool loading = false;

  void setLoading(bool v) {
    loading = v;
    notifyListeners();
  }

  Future<Map<String, dynamic>> login() async {
    setLoading(true);
    try {
      final resp = await repository.login(
        user: loginUserController.text.trim(),
        password: loginPasswordController.text,
      );
      return resp;
    } finally {
      setLoading(false);
    }
  }

  /// Retorna userId criado na parte 1
  Future<int> registerPart1() async {
    setLoading(true);
    try {
      final userId = await repository.registerCredentials(
        user: regUserController.text.trim(),
        password: regPasswordController.text,
        passwordConfirm: regPasswordConfirmController.text,
      );
      return userId;
    } finally {
      setLoading(false);
    }
  }

  Future<int> registerPart2({required int userId}) async {
    setLoading(true);
    try {
      final createdCustomerId = await repository.registerProfile(
        userId: userId,
        name: nameController.text.trim(),
        profile: profileController.text.trim(),
        phone: phoneController.text.trim(),
        dateBirth: dateBirthController.text.trim(),
        zipCode: zipCodeController.text.trim(),
        address: addressController.text.trim(),
        cpf: cpfController.text.trim(),
        whatsapp: whatsappController.text.trim(),
        mail: mailController.text.trim(),
      );
      return createdCustomerId;
    } finally {
      setLoading(false);
    }
  }

  void disposeAll() {
    loginUserController.dispose();
    loginPasswordController.dispose();
    regUserController.dispose();
    regPasswordController.dispose();
    regPasswordConfirmController.dispose();
    nameController.dispose();
    profileController.dispose();
    phoneController.dispose();
    dateBirthController.dispose();
    zipCodeController.dispose();
    addressController.dispose();
    cpfController.dispose();
    whatsappController.dispose();
    mailController.dispose();
  }
}

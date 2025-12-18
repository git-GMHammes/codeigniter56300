import 'dart:io';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:image_picker/image_picker.dart';

/// Widget para a Etapa 2 do cadastro:  Dados do perfil e upload de foto
class RegisterStep2Card extends StatefulWidget {
  final VoidCallback onBack;
  final void Function({
    required String name,
    required String cpf,
    required String mail,
    required String phone,
    required String whatsapp,
    required DateTime?  dateBirth,
    required String zipCode,
    required String address,
    required String?  profileImagePath,
  }) onComplete;

  const RegisterStep2Card({
    super.key,
    required this.onBack,
    required this.onComplete,
  });

  @override
  State<RegisterStep2Card> createState() => _RegisterStep2CardState();
}

class _RegisterStep2CardState extends State<RegisterStep2Card> {
  // ══════════════════════════════════════════════════════════════════════════
  // CONTROLLERS & STATE
  // ══════════════════════════════════════════════════════════════════════════
  final _formKey = GlobalKey<FormState>();
  final _imagePicker = ImagePicker();

  late final TextEditingController _nameController;
  late final TextEditingController _cpfController;
  late final TextEditingController _mailController;
  late final TextEditingController _phoneController;
  late final TextEditingController _whatsappController;
  late final TextEditingController _dateBirthController;
  late final TextEditingController _zipCodeController;
  late final TextEditingController _addressController;

  File? _profileImage;
  DateTime? _selectedDate;
  bool _isLoading = false;
  bool _sameAsPhone = false;

  // ══════════════════════════════════════════════════════════════════════════
  // LIFECYCLE
  // ══════════════════════════════════════════════════════════════════════════
  @override
  void initState() {
    super.initState();
    _nameController = TextEditingController();
    _cpfController = TextEditingController();
    _mailController = TextEditingController();
    _phoneController = TextEditingController();
    _whatsappController = TextEditingController();
    _dateBirthController = TextEditingController();
    _zipCodeController = TextEditingController();
    _addressController = TextEditingController();
  }

  @override
  void dispose() {
    _nameController.dispose();
    _cpfController.dispose();
    _mailController.dispose();
    _phoneController.dispose();
    _whatsappController.dispose();
    _dateBirthController.dispose();
    _zipCodeController.dispose();
    _addressController. dispose();
    super.dispose();
  }

  // ══════════════════════════════════════════════════════════════════════════
  // FORMATTERS
  // ══════════════════════════════════════════════════════════════════════════

  /// Formata CPF:  000.000.000-00
  String _formatCpf(String value) {
    value = value.replaceAll(RegExp(r'\D'), '');
    if (value.length > 11) value = value.substring(0, 11);

    if (value.length > 9) {
      return '${value.substring(0, 3)}.${value.substring(3, 6)}.${value.substring(6, 9)}-${value.substring(9)}';
    } else if (value.length > 6) {
      return '${value.substring(0, 3)}.${value.substring(3, 6)}.${value.substring(6)}';
    } else if (value.length > 3) {
      return '${value.substring(0, 3)}.${value.substring(3)}';
    }
    return value;
  }

  /// Formata telefone: (00) 00000-0000
  String _formatPhone(String value) {
    value = value.replaceAll(RegExp(r'\D'), '');
    if (value.length > 11) value = value.substring(0, 11);

    if (value. length > 7) {
      return '(${value.substring(0, 2)}) ${value.substring(2, 7)}-${value.substring(7)}';
    } else if (value.length > 2) {
      return '(${value. substring(0, 2)}) ${value.substring(2)}';
    } else if (value. length > 0) {
      return '(${value}';
    }
    return value;
  }

  /// Formata CEP: 00000-000
  String _formatZipCode(String value) {
    value = value.replaceAll(RegExp(r'\D'), '');
    if (value. length > 8) value = value.substring(0, 8);

    if (value.length > 5) {
      return '${value.substring(0, 5)}-${value.substring(5)}';
    }
    return value;
  }

  // ══════════════════════════════════════════════════════════════════════════
  // VALIDATORS
  // ══════════════════════════════════════════════════════════════════════════
  String? _validateName(String? value) {
    if (value == null || value.trim().isEmpty) {
      return 'Informe o nome completo';
    }
    if (value.trim().length < 3) {
      return 'Nome deve ter pelo menos 3 caracteres';
    }
    if (value.trim().length > 150) {
      return 'Nome muito longo';
    }
    return null;
  }

  String? _validateCpf(String?  value) {
    if (value == null || value.isEmpty) {
      return null; // CPF é opcional
    }
    final cleanCpf = value.replaceAll(RegExp(r'\D'), '');
    if (cleanCpf.length != 11) {
      return 'CPF inválido';
    }
    return null;
  }

  String? _validateEmail(String? value) {
    if (value == null || value.trim().isEmpty) {
      return null; // Email é opcional
    }
    final emailRegex = RegExp(r'^[\w-\.]+@([\w-]+\. )+[\w-]{2,4}$');
    if (!emailRegex.hasMatch(value.trim())) {
      return 'E-mail inválido';
    }
    if (value.trim().length > 150) {
      return 'E-mail muito longo';
    }
    return null;
  }

  String? _validatePhone(String? value) {
    if (value == null || value. isEmpty) {
      return null; // Telefone é opcional
    }
    final cleanPhone = value.replaceAll(RegExp(r'\D'), '');
    if (cleanPhone.length < 10 || cleanPhone.length > 11) {
      return 'Telefone inválido';
    }
    return null;
  }

  // ══════════════════════════════════════════════════════════════════════════
  // ACTIONS
  // ══════════════════════════════════════════════════════════════════════════

  /// Seleciona imagem da galeria ou câmera
  Future<void> _pickImage(ImageSource source) async {
    try {
      final XFile? pickedFile = await _imagePicker.pickImage(
        source: source,
        maxWidth: 800,
        maxHeight: 800,
        imageQuality: 85,
      );

      if (pickedFile != null) {
        setState(() {
          _profileImage = File(pickedFile. path);
        });
      }
    } catch (e) {
      _showSnackBar('Erro ao selecionar imagem: $e');
    }
  }

  /// Exibe opções de seleção de imagem
  void _showImagePicker() {
    final theme = Theme.of(context);
    final isDark = theme. brightness == Brightness. dark;

    showModalBottomSheet(
      context: context,
      shape: const RoundedRectangleBorder(
        borderRadius:  BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (context) => SafeArea(
        child:  Padding(
          padding: const EdgeInsets.symmetric(vertical: 20),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Text(
                'Selecionar Foto',
                style:  theme.textTheme. titleLarge?.copyWith(
                  fontWeight: FontWeight. bold,
                ),
              ),
              const SizedBox(height: 20),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                children: [
                  _buildImageOption(
                    icon: Icons.camera_alt,
                    label: 'Câmera',
                    onTap: () {
                      Navigator.pop(context);
                      _pickImage(ImageSource. camera);
                    },
                    isDark: isDark,
                  ),
                  _buildImageOption(
                    icon: Icons.photo_library,
                    label: 'Galeria',
                    onTap:  () {
                      Navigator.pop(context);
                      _pickImage(ImageSource.gallery);
                    },
                    isDark: isDark,
                  ),
                  if (_profileImage != null)
                    _buildImageOption(
                      icon: Icons. delete,
                      label: 'Remover',
                      onTap: () {
                        Navigator.pop(context);
                        setState(() => _profileImage = null);
                      },
                      isDark: isDark,
                      isDestructive:  true,
                    ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildImageOption({
    required IconData icon,
    required String label,
    required VoidCallback onTap,
    required bool isDark,
    bool isDestructive = false,
  }) {
    final color = isDestructive
        ? Colors.red
        : (isDark ? Colors. purpleAccent : Colors.indigo);

    return InkWell(
      onTap:  onTap,
      borderRadius: BorderRadius. circular(12),
      child:  Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: color. withOpacity(0.1),
                shape: BoxShape. circle,
              ),
              child: Icon(icon, color: color, size: 32),
            ),
            const SizedBox(height: 8),
            Text(
              label,
              style: TextStyle(
                color: color,
                fontWeight: FontWeight. w500,
              ),
            ),
          ],
        ),
      ),
    );
  }

  /// Seleciona data de nascimento
  Future<void> _selectDate() async {
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: _selectedDate ??  DateTime(2000),
      firstDate: DateTime(1900),
      lastDate: DateTime. now(),
      helpText: 'Selecione a data de nascimento',
      cancelText: 'Cancelar',
      confirmText: 'Confirmar',
    );

    if (picked != null) {
      setState(() {
        _selectedDate = picked;
        _dateBirthController.text =
            '${picked.day. toString().padLeft(2, '0')}/${picked.month. toString().padLeft(2, '0')}/${picked.year}';
      });
    }
  }

  /// Finaliza o cadastro
  Future<void> _handleComplete() async {
    if (! _formKey.currentState! .validate()) return;

    setState(() => _isLoading = true);

    // Simula processamento
    await Future.delayed(const Duration(milliseconds: 800));

    if (mounted) {
      setState(() => _isLoading = false);
      widget.onComplete(
        name: _nameController. text. trim(),
        cpf: _cpfController.text.replaceAll(RegExp(r'\D'), ''),
        mail: _mailController. text.trim(),
        phone: _phoneController.text.replaceAll(RegExp(r'\D'), ''),
        whatsapp: _whatsappController.text.replaceAll(RegExp(r'\D'), ''),
        dateBirth: _selectedDate,
        zipCode:  _zipCodeController. text.replaceAll(RegExp(r'\D'), ''),
        address: _addressController. text.trim(),
        profileImagePath:  _profileImage?.path,
      );
    }
  }

  void _showSnackBar(String message) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(message)),
    );
  }

  // ══════════════════════════════════════════════════════════════════════════
  // BUILDERS
  // ══════════════════════════════════════════════════════════════════════════

  /// Avatar com upload de foto
  Widget _buildAvatarPicker(ThemeData theme, bool isDark) {
    final iconColor = isDark ?  Colors.purpleAccent : Colors.indigo;

    return GestureDetector(
      onTap: _showImagePicker,
      child: Stack(
        children:  [
          // Avatar principal
          Container(
            width: 120,
            height:  120,
            decoration: BoxDecoration(
              shape: BoxShape.circle,
              color:  iconColor.withOpacity(0.1),
              border: Border.all(
                color: iconColor. withOpacity(0.3),
                width: 3,
              ),
              boxShadow:  [
                BoxShadow(
                  color: iconColor.withOpacity(0.2),
                  blurRadius: 12,
                  spreadRadius: 2,
                ),
              ],
            ),
            child:  ClipOval(
              child: _profileImage != null
                  ? Image. file(
                      _profileImage!,
                      fit:  BoxFit.cover,
                      width: 120,
                      height: 120,
                    )
                  :  Icon(
                      Icons.person,
                      size:  60,
                      color: iconColor,
                    ),
            ),
          ),
          // Botão de câmera
          Positioned(
            bottom: 0,
            right: 0,
            child: Container(
              padding: const EdgeInsets.all(8),
              decoration:  BoxDecoration(
                color: iconColor,
                shape: BoxShape.circle,
                border: Border.all(
                  color:  isDark ? Colors.grey.shade900 : Colors. white,
                  width: 2,
                ),
              ),
              child: Icon(
                _profileImage != null ?  Icons.edit : Icons.camera_alt,
                size: 20,
                color:  Colors.white,
              ),
            ),
          ),
        ],
      ),
    );
  }

  /// Campo de texto genérico
  Widget _buildTextField({
    required TextEditingController controller,
    required String label,
    String? hint,
    IconData?  prefixIcon,
    TextInputType?  keyboardType,
    String? Function(String?)? validator,
    List<TextInputFormatter>? inputFormatters,
    void Function(String)? onChanged,
    int maxLines = 1,
    bool isDark = false,
  }) {
    return TextFormField(
      controller: controller,
      validator: validator,
      keyboardType: keyboardType,
      inputFormatters: inputFormatters,
      onChanged: onChanged,
      maxLines: maxLines,
      decoration: InputDecoration(
        labelText:  label,
        hintText: hint,
        prefixIcon: prefixIcon != null ?  Icon(prefixIcon) : null,
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
        ),
        filled: true,
        fillColor: isDark ? Colors.grey.shade900 :  Colors.grey.shade50,
      ),
    );
  }

  /// Campo de data
  Widget _buildDateField(ThemeData theme, bool isDark) {
    return TextFormField(
      controller: _dateBirthController,
      readOnly: true,
      onTap: _selectDate,
      decoration: InputDecoration(
        labelText:  'Data de Nascimento',
        hintText: 'DD/MM/AAAA',
        prefixIcon: const Icon(Icons.calendar_today),
        suffixIcon: const Icon(Icons.arrow_drop_down),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
        ),
        filled: true,
        fillColor: isDark ? Colors.grey.shade900 :  Colors.grey.shade50,
      ),
    );
  }

  /// Checkbox "WhatsApp igual ao telefone"
  Widget _buildSameAsPhoneCheckbox(ThemeData theme, bool isDark) {
    return CheckboxListTile(
      value: _sameAsPhone,
      onChanged: (value) {
        setState(() {
          _sameAsPhone = value ??  false;
          if (_sameAsPhone) {
            _whatsappController.text = _phoneController. text;
          }
        });
      },
      title: Text(
        'WhatsApp igual ao telefone',
        style: TextStyle(
          fontSize: 14,
          color: isDark ? Colors.grey.shade300 : Colors.grey.shade700,
        ),
      ),
      controlAffinity: ListTileControlAffinity.leading,
      contentPadding: EdgeInsets.zero,
      dense: true,
    );
  }

  /// Botões de navegação
  Widget _buildNavigationButtons(ThemeData theme, bool isDark) {
    return Row(
      children: [
        // Botão Voltar
        Expanded(
          child:  OutlinedButton. icon(
            onPressed: _isLoading ? null : widget.onBack,
            icon: const Icon(Icons.arrow_back),
            label: const Text('Voltar'),
            style: OutlinedButton.styleFrom(
              padding: const EdgeInsets.symmetric(vertical: 14),
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(12),
              ),
            ),
          ),
        ),
        const SizedBox(width: 16),
        // Botão Cadastrar
        Expanded(
          flex: 2,
          child:  FilledButton. icon(
            onPressed: _isLoading ? null : _handleComplete,
            icon: _isLoading
                ? SizedBox(
                    width: 20,
                    height: 20,
                    child: CircularProgressIndicator(
                      strokeWidth: 2,
                      color:  isDark ? Colors. white70 : Colors.white,
                    ),
                  )
                : const Icon(Icons.check),
            label: Text(
              _isLoading ? 'Cadastrando...' : 'Finalizar Cadastro',
              style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w600),
            ),
            style: FilledButton.styleFrom(
              padding: const EdgeInsets.symmetric(vertical: 14),
              shape:  RoundedRectangleBorder(
                borderRadius:  BorderRadius.circular(12),
              ),
            ),
          ),
        ),
      ],
    );
  }

  // ══════════════════════════════════════════════════════════════════════════
  // BUILD PRINCIPAL
  // ══════════════════════════════════════════════════════════════════════════
  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final isDark = theme.brightness == Brightness.dark;

    return Card(
      elevation:  isDark ? 2 : 4,
      shadowColor:  isDark ? Colors. black45 : Colors.black26,
      shape:  RoundedRectangleBorder(
        borderRadius:  BorderRadius.circular(20),
      ),
      child:  Padding(
        padding: const EdgeInsets.all(24),
        child: Form(
          key: _formKey,
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              // ─────────────────────────────────────────────────────────────────
              // AVATAR / UPLOAD FOTO
              // ─────────────────────────────────────────────────────────────────
              _buildAvatarPicker(theme, isDark),
              const SizedBox(height: 8),
              Text(
                'Toque para adicionar foto',
                style: TextStyle(
                  fontSize: 12,
                  color: isDark ? Colors.grey.shade400 : Colors.grey.shade600,
                ),
              ),
              const SizedBox(height: 20),

              // ─────────────────────────────────────────────────────────────────
              // TÍTULO
              // ─────────────────────────────────────────────────────────────────
              Text(
                'Complete seu Perfil',
                style: theme.textTheme. headlineSmall?.copyWith(
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(height: 8),
              Text(
                'Etapa 2: Dados pessoais',
                style: theme.textTheme. bodyMedium?. copyWith(
                  color: isDark ? Colors.grey. shade400 : Colors.grey.shade600,
                ),
              ),
              const SizedBox(height: 24),

              // ─────────────────────────────────────────────────────────────────
              // NOME COMPLETO *
              // ─────────────────────────────────────────────────────────────────
              _buildTextField(
                controller:  _nameController,
                label: 'Nome Completo *',
                hint:  'Digite seu nome completo',
                prefixIcon: Icons. person_outline,
                validator: _validateName,
                isDark: isDark,
              ),
              const SizedBox(height: 16),

              // ─────────────────────────────────────────────────────────────────
              // CPF
              // ─────────────────────────────────────────────────────────────────
              _buildTextField(
                controller:  _cpfController,
                label: 'CPF',
                hint: '000.000.000-00',
                prefixIcon: Icons. badge_outlined,
                keyboardType: TextInputType.number,
                validator: _validateCpf,
                onChanged: (value) {
                  final formatted = _formatCpf(value);
                  if (formatted != value) {
                    _cpfController.value = TextEditingValue(
                      text: formatted,
                      selection: TextSelection.collapsed(offset: formatted. length),
                    );
                  }
                },
                isDark: isDark,
              ),
              const SizedBox(height: 16),

              // ─────────────────────────────────────────────────────────────────
              // E-MAIL
              // ─────────────────────────────────────────────────────────────────
              _buildTextField(
                controller: _mailController,
                label: 'E-mail',
                hint: 'seu@email.com',
                prefixIcon: Icons.email_outlined,
                keyboardType: TextInputType.emailAddress,
                validator: _validateEmail,
                isDark: isDark,
              ),
              const SizedBox(height: 16),

              // ─────────────────────────────────────────────────────────────────
              // TELEFONE
              // ─────────────────────────────────────────────────────────────────
              _buildTextField(
                controller: _phoneController,
                label:  'Telefone',
                hint:  '(00) 00000-0000',
                prefixIcon: Icons.phone_outlined,
                keyboardType: TextInputType.phone,
                validator: _validatePhone,
                onChanged: (value) {
                  final formatted = _formatPhone(value);
                  if (formatted != value) {
                    _phoneController. value = TextEditingValue(
                      text: formatted,
                      selection: TextSelection.collapsed(offset: formatted.length),
                    );
                  }
                  if (_sameAsPhone) {
                    _whatsappController. text = formatted;
                  }
                },
                isDark: isDark,
              ),
              const SizedBox(height: 8),

              // ─────────────────────────────────────────────────────────────────
              // CHECKBOX WHATSAPP = TELEFONE
              // ─────────────────────────────────────────────────────────────────
              _buildSameAsPhoneCheckbox(theme, isDark),
              const SizedBox(height:  8),

              // ─────────────────────────────────────────────────────────────────
              // WHATSAPP
              // ─────────────────────────────────────────────────────────────────
              _buildTextField(
                controller: _whatsappController,
                label: 'WhatsApp',
                hint: '(00) 00000-0000',
                prefixIcon: Icons.chat_outlined,
                keyboardType: TextInputType.phone,
                validator: _validatePhone,
                onChanged: (value) {
                  final formatted = _formatPhone(value);
                  if (formatted != value) {
                    _whatsappController.value = TextEditingValue(
                      text:  formatted,
                      selection: TextSelection. collapsed(offset: formatted.length),
                    );
                  }
                },
                isDark:  isDark,
              ),
              const SizedBox(height:  16),

              // ─────────────────────────────────────────────────────────────────
              // DATA DE NASCIMENTO
              // ─────────────────────────────────────────────────────────────────
              _buildDateField(theme, isDark),
              const SizedBox(height: 16),

              // ─────────────────────────────────────────────────────────────────
              // CEP
              // ─────────────────────────────────────────────────────────────────
              _buildTextField(
                controller:  _zipCodeController,
                label: 'CEP',
                hint: '00000-000',
                prefixIcon: Icons. location_on_outlined,
                keyboardType: TextInputType.number,
                onChanged: (value) {
                  final formatted = _formatZipCode(value);
                  if (formatted != value) {
                    _zipCodeController.value = TextEditingValue(
                      text:  formatted,
                      selection: TextSelection. collapsed(offset: formatted.length),
                    );
                  }
                },
                isDark: isDark,
              ),
              const SizedBox(height: 16),

              // ─────────────────────────────────────────────────────────────────
              // ENDEREÇO
              // ─────────────────────────────────────────────────────────────────
              _buildTextField(
                controller: _addressController,
                label: 'Endereço',
                hint: 'Rua, número, bairro, cidade',
                prefixIcon: Icons. home_outlined,
                isDark: isDark,
              ),
              const SizedBox(height: 24),

              // ─────────────────────────────────────────────────────────────────
              // BOTÕES DE NAVEGAÇÃO
              // ─────────────────────────────────────────────────────────────────
              _buildNavigationButtons(theme, isDark),
            ],
          ),
        ),
      ),
    );
  }
}
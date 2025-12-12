// C:\laragon\www\hform\src\public\script\react_modelo_v3\frontend\src\components\HForm\HformInputTextMask\index.jsx
import React from 'react';
import { useFormContext } from 'react-hook-form';

/**
 * Componente de campo de entrada com máscara reutilizável
 * @param {Object} props - Propriedades do componente
 * @param {string} props.name - Nome do campo (usado para register e id)
 * @param {string} props.label - Texto do label do campo
 * @param {string} props.accessKey - Tecla de acesso rápido
 * @param {string} props.placeholder - Texto de placeholder
 * @param {string|number} props.maxLength - Comprimento máximo do campo
 * @param {string|number} props.minLength - Comprimento mínimo do campo
 * @param {boolean} props.required - Se o campo é obrigatório
 * @param {boolean} props.readOnly - Se o campo é somente leitura
 * @param {boolean} props.disabled - Se o campo está desabilitado
 * @param {string|number} props.tabIndex - Índice de tabulação
 * @param {string} props.pattern - Padrão de validação
 * @param {string} props.title - Tooltip do campo
 * @param {function} props.formatarMascara - Função para formatar a máscara (opcional)
 * @param {string} props.errorMessage - Mensagem de erro personalizada
 * @param {Array} props.dataListOptions - Opções para o datalist
 * @param {string} props.tipoMask - Tipo de máscara predefinida ("TelefoneFixo", "TelefoneCelular", "CPF", "CNPJ", "Padrao")
 * @returns {JSX.Element} Componente de campo com máscara
 */
const HformInputTextMask = ({
    name,
    label,
    accessKey,
    placeholder,
    maxLength,
    minLength,
    required = false,
    readOnly = false,
    disabled = false,
    tabIndex,
    pattern,
    title,
    formatarMascara,
    errorMessage,
    dataListOptions = [],
    tipoMask = "Padrao" // Valor padrão
}) => {
    // Definições padrão baseadas no tipo de máscara
    const definicoesMascara = {
        Padrao: {
            pattern: "\\d{3}\\.\\d{3}\\.\\d{3}\\/\\d{3}\\.\\d{3}",
            title: "Campo com máscara NNN.NNN.NNN/NNN.NNN",
            errorMessage: "O formato deve ser NNN.NNN.NNN/NNN.NNN",
            placeholder: "123.456.789/123.456",
            maxLength: "19",
            minLength: "19",
            formatarMascara: (value) => {
                if (!value) return '';
                const apenasNumeros = value.replace(/\D/g, '');
                let resultado = '';

                if (apenasNumeros.length > 0) {
                    resultado = apenasNumeros.slice(0, 3);

                    if (apenasNumeros.length > 3) {
                        resultado += '.' + apenasNumeros.slice(3, 6);
                    }

                    if (apenasNumeros.length > 6) {
                        resultado += '.' + apenasNumeros.slice(6, 9);
                    }

                    if (apenasNumeros.length > 9) {
                        resultado += '/' + apenasNumeros.slice(9, 12);
                    }

                    if (apenasNumeros.length > 12) {
                        resultado += '.' + apenasNumeros.slice(12, 15);
                    }
                }

                return resultado;
            }
        },
        CPF: {
            pattern: "\\d{3}\\.\\d{3}\\.\\d{3}-\\d{2}",
            title: "Campo com máscara de CPF (123.456.789-00)",
            errorMessage: "O formato deve ser 123.456.789-00",
            placeholder: "123.456.789-00",
            maxLength: "14",
            minLength: "14",
            formatarMascara: (value) => {
                if (!value) return '';
                const apenasNumeros = value.replace(/\D/g, '');
                let resultado = '';

                if (apenasNumeros.length > 0) {
                    resultado = apenasNumeros.slice(0, 3);

                    if (apenasNumeros.length > 3) {
                        resultado += '.' + apenasNumeros.slice(3, 6);
                    }

                    if (apenasNumeros.length > 6) {
                        resultado += '.' + apenasNumeros.slice(6, 9);
                    }

                    if (apenasNumeros.length > 9) {
                        resultado += '-' + apenasNumeros.slice(9, 11);
                    }
                }

                return resultado;
            }
        },
        CNPJ: {
            pattern: "\\d{2}\\.\\d{3}\\.\\d{3}\\/\\d{4}-\\d{2}",
            title: "Campo com máscara de CNPJ (12.345.678/0001-90)",
            errorMessage: "O formato deve ser 12.345.678/0001-90",
            placeholder: "12.345.678/0001-90",
            maxLength: "18",
            minLength: "18",
            formatarMascara: (value) => {
                if (!value) return '';
                const apenasNumeros = value.replace(/\D/g, '');
                let resultado = '';

                if (apenasNumeros.length > 0) {
                    resultado = apenasNumeros.slice(0, 2);

                    if (apenasNumeros.length > 2) {
                        resultado += '.' + apenasNumeros.slice(2, 5);
                    }

                    if (apenasNumeros.length > 5) {
                        resultado += '.' + apenasNumeros.slice(5, 8);
                    }

                    if (apenasNumeros.length > 8) {
                        resultado += '/' + apenasNumeros.slice(8, 12);
                    }

                    if (apenasNumeros.length > 12) {
                        resultado += '-' + apenasNumeros.slice(12, 14);
                    }
                }

                return resultado;
            }
        },
        TelefoneFixo: {
            pattern: "\\(\\d{2}\\)\\d{4}-\\d{4}",
            title: "Campo com máscara de telefone fixo ((11)1234-5678)",
            errorMessage: "O formato deve ser (11)1234-5678",
            placeholder: "(11)1234-5678",
            maxLength: "13",
            minLength: "13",
            formatarMascara: (value) => {
                if (!value) return '';
                const apenasNumeros = value.replace(/\D/g, '');
                let resultado = '';

                if (apenasNumeros.length > 0) {
                    resultado = '(' + apenasNumeros.slice(0, 2) + ')';

                    if (apenasNumeros.length > 2) {
                        resultado += apenasNumeros.slice(2, 6);
                    }

                    if (apenasNumeros.length > 6) {
                        resultado += '-' + apenasNumeros.slice(6, 10);
                    }
                }

                return resultado;
            }
        },
        TelefoneCelular: {
            pattern: "\\(\\d{2}\\)9\\d{4}-\\d{4}",
            title: "Campo com máscara de telefone celular ((11)91234-5678)",
            errorMessage: "O formato deve ser (11)91234-5678 (9 obrigatório)",
            placeholder: "(11)91234-5678",
            maxLength: "14",
            minLength: "14",
            formatarMascara: (value) => {
                if (!value) return '';
                const apenasNumeros = value.replace(/\D/g, '');
                let resultado = '';

                if (apenasNumeros.length > 0) {
                    resultado = '(' + apenasNumeros.slice(0, 2) + ')';

                    if (apenasNumeros.length > 2) {
                        // Garantir que o terceiro dígito seja 9
                        const terceiroDig = apenasNumeros.length > 2 ? '9' : '';
                        resultado += terceiroDig;

                        if (apenasNumeros.length > 3) {
                            resultado += apenasNumeros.slice(3, 7);
                        }
                    }

                    if (apenasNumeros.length > 7) {
                        resultado += '-' + apenasNumeros.slice(7, 11);
                    }
                }

                return resultado;
            }
        }
    };

    // Obtém os métodos do react-hook-form do contexto do formulário
    // Movemos este bloco para depois das definições de máscara para garantir
    // que definicoesMascara esteja definido antes de ser usado
    const methods = useFormContext();
    
    // Verificação de segurança para garantir que o contexto do formulário exista
    if (!methods) {
        console.error('HformInputTextMask deve ser usado dentro de um FormProvider');
        return (
            <div className="form-group mb-3">
                <div className="alert alert-danger">
                    Erro: FormProvider não encontrado. Este componente deve ser usado dentro de um FormProvider.
                </div>
            </div>
        );
    }
    
    const {
        register,
        formState: { errors },
        watch,
        setValue,
    } = methods;

    // Define os valores padrão com base no tipo de máscara
    const maskConfig = definicoesMascara[tipoMask] || definicoesMascara.Padrao;

    // Usa os valores fornecidos ou os valores padrão do tipo de máscara
    const maskPattern = pattern || maskConfig.pattern;
    const maskTitle = title || maskConfig.title;
    const maskErrorMessage = errorMessage || maskConfig.errorMessage;
    const maskPlaceholder = placeholder || maskConfig.placeholder;
    const maskMaxLength = maxLength || maskConfig.maxLength;
    const maskMinLength = minLength || maskConfig.minLength;

    // Usa a função de formatação fornecida ou a padrão com base no tipo
    const formatadorMascara = formatarMascara || maskConfig.formatarMascara;

    // Funções para registrar logs
    const handleFocus = (fieldName, value) => {
        console.log('onFocus:', fieldName, value);
    };

    const handleChange = (fieldName, value) => {
        console.log('onChange:', fieldName, value);
    };

    const handleBlur = (fieldName, value) => {
        console.log('onBlur:', fieldName, value);
    };

    // Manipular o evento de mudança no campo com máscara
    const handleMascaraChange = (e) => {
        const { value } = e.target;
        const mascaraFormatada = formatadorMascara(value);

        // Atualiza o valor do campo usando setValue do react-hook-form
        setValue(name, mascaraFormatada, {
            shouldValidate: true,
            shouldDirty: true
        });

        // Registra o evento de mudança
        handleChange(name, mascaraFormatada);
    };

    // Configuração do registro do campo
    const fieldRegister = register(name, {
        required: required ? 'Este campo é obrigatório' : false,
        pattern: {
            value: new RegExp(maskPattern),
            message: maskErrorMessage
        },
        onBlur: (e) => handleBlur(name, e.target.value),
    });

    // ID do datalist
    const datalistId = `${name}-list`;

    // Valor atual do campo
    const currentValue = watch(name) || '';

    return (
        <div className="form-group mb-3">
            {label && (
                <label htmlFor={name} className="form-label">
                    {label}
                </label>
            )}
            <input
                type="text"
                className={`form-control ${errors[name] ? 'is-invalid' : ''}`}
                {...fieldRegister}
                onFocus={() => handleFocus(name, currentValue)}
                onChange={(e) => {
                    handleMascaraChange(e);
                }}
                accessKey={accessKey}
                autoComplete="off"
                contentEditable="true"
                dir="ltr"
                dirname={`${name}.dir`}
                draggable="false"
                form="hook-form"
                id={name}
                lang="pt-BR"
                list={dataListOptions?.length > 0 ? datalistId : undefined}
                maxLength={maskMaxLength}
                minLength={maskMinLength}
                name={name}
                pattern={maskPattern}
                placeholder={maskPlaceholder}
                required={required}
                readOnly={readOnly}
                disabled={disabled}
                size="40"
                spellCheck="false"
                tabIndex={tabIndex}
                title={maskTitle}
                translate="no"
                value={currentValue}
            />

            {dataListOptions?.length > 0 && (
                <datalist id={datalistId}>
                    {dataListOptions.map((option, index) => (
                        <option key={index} value={option} />
                    ))}
                </datalist>
            )}

            {errors[name] && (
                <div className="invalid-feedback">
                    {errors[name].message}
                </div>
            )}
        </div>
    );
};

export default HformInputTextMask;
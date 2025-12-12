-- Script CORRIGIDO
SELECT `um`.`id` AS `id`,
    `uc`.`id` AS `uc_id`,
    `um`.`user` AS `um_user`,
    `uc`.`user_id` AS `uc_user_id`,
    `uc`.`name` AS `uc_name`,
    `uc`.`cpf` AS `uc_cpf`,
    `uc`.`whatsapp` AS `uc_whatsapp`,
    `uc`.`profile` AS `uc_profile`,
    `uc`.`mail` AS `uc_mail`,
    `uc`.`phone` AS `uc_phone`,
    `uc`.`date_birth` AS `uc_date_birth`,
    `uc`.`zip_code` AS `uc_zip_code`,
    `uc`.`address` AS `uc_address`,
    `um`.`created_at` AS `created_at`,
    `um`.`updated_at` AS `updated_at`,
    `um`.`deleted_at` AS `deleted_at` -- `um`.`password` AS `um_password`,
    -- `uc`.`created_at` AS `uc_created_at`,
    -- `uc`.`updated_at` AS `uc_updated_at`,
    -- `uc`. `deleted_at` AS `uc_deleted_at`
FROM `user_customer` AS `uc`
    JOIN `user_management` AS `um` ON `uc`.`user_id` = `um`.`id`;
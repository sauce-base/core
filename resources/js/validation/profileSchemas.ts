import { z } from 'zod';
import { emailSchema, nameSchema, passwordSchema } from './commonSchemas';

export const updateProfileInformationSchema = z.object({
    name: nameSchema,
    email: emailSchema,
});

export const updatePasswordSchema = z
    .object({
        current_password: z
            .string()
            .min(1, 'Please enter your current password'),
        password: passwordSchema,
        password_confirmation: z
            .string()
            .min(1, 'Please confirm your new password'),
    })
    .refine((data) => data.password === data.password_confirmation, {
        message: "New passwords don't match - please check both fields",
        path: ['password_confirmation'],
    });

export const deleteUserSchema = z.object({
    password: z
        .string()
        .min(1, 'Please enter your password to confirm deletion'),
});

export type UpdateProfileInformationFormData = z.infer<
    typeof updateProfileInformationSchema
>;
export type UpdatePasswordFormData = z.infer<typeof updatePasswordSchema>;
export type DeleteUserFormData = z.infer<typeof deleteUserSchema>;

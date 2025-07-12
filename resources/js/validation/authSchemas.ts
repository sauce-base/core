import { z } from 'zod';
import { emailSchema, nameSchema, passwordSchema } from './commonSchemas';

export const loginSchema = z.object({
    email: emailSchema,
    password: z
        .string({ required_error: 'Please enter your password' })
        .min(1, 'Please enter your password'),
    remember: z.boolean().optional(),
});

export const registerSchema = z
    .object({
        name: nameSchema,
        email: emailSchema,
        password: passwordSchema,
        password_confirmation: z
            .string()
            .min(1, 'Please confirm your password'),
    })
    .refine((data) => data.password === data.password_confirmation, {
        message: "Passwords don't match - please check both fields",
        path: ['password_confirmation'],
    });

export const forgotPasswordSchema = z.object({
    email: emailSchema,
});

export const resetPasswordSchema = z
    .object({
        token: z.string().min(1, 'Reset token is missing'),
        email: emailSchema,
        password: passwordSchema,
        password_confirmation: z
            .string()
            .min(1, 'Please confirm your password'),
    })
    .refine((data) => data.password === data.password_confirmation, {
        message: "Passwords don't match - please check both fields",
        path: ['password_confirmation'],
    });

export const confirmPasswordSchema = z.object({
    password: z.string().min(1, 'Please enter your password'),
});

export type LoginFormData = z.infer<typeof loginSchema>;
export type RegisterFormData = z.infer<typeof registerSchema>;
export type ForgotPasswordFormData = z.infer<typeof forgotPasswordSchema>;
export type ResetPasswordFormData = z.infer<typeof resetPasswordSchema>;
export type ConfirmPasswordFormData = z.infer<typeof confirmPasswordSchema>;

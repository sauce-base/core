import { z } from 'zod';

export const emailSchema = z
    .string({ required_error: 'Please enter your email address' })
    .min(1, 'Please enter your email address')
    .email('Please enter a valid email address (like john@example.com)');

export const passwordSchema = z
    .string({ required_error: 'Please enter your password' })
    .min(8, 'Password needs to be at least 8 characters long')
    .regex(/[A-Za-z]/, 'Password must include at least one letter')
    .regex(/\d/, 'Password must include at least one number');

export const nameSchema = z
    .string({ required_error: 'Please enter your name' })
    .min(1, 'Please enter your name')
    .min(2, 'Name should be at least 2 characters')
    .max(255, 'Name is too long (maximum 255 characters)')
    .regex(/^[a-zA-Z\s]*$/, 'Name can only contain letters and spaces');

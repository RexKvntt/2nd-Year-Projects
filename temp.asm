section .data
    msg db "Enter temperature (F): ", 0
    msg_len equ $ - msg
    newline db 10

section .bss
    input resb 16

section .text
    global _start

_start:
    ; print prompt
    mov eax, 4
    mov ebx, 1
    mov ecx, msg
    mov edx, msg_len
    int 0x80

    ; read input
    mov eax, 3
    mov ebx, 0
    mov ecx, input
    mov edx, 16
    int 0x80

    ; convert ASCII to integer (simple)
    mov esi, input
    xor eax, eax
convert_loop:
    mov bl, [esi]
    cmp bl, 10
    je done_convert
    sub bl, '0'
    imul eax, eax, 10
    add eax, ebx
    inc esi
    jmp convert_loop

done_convert:
    ; F → C: C = (F - 32) * 5 / 9
    sub eax, 32
    imul eax, eax, 5
    cdq
    mov ebx, 9
    idiv ebx   ; result in eax

    ; exit (result stays in eax)
    mov ebx, eax
    mov eax, 1
    int 0x80

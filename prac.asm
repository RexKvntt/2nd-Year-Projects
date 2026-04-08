section .data
    prompt_name db "Enter name: "
    prompt_name_len equ $ - prompt_name

    prompt_score db "Enter score (0-100): "
    prompt_score_len equ $ - prompt_score

    label_name db "Name: "
    label_name_len equ $ - label_name

    label_score db "Score: "
    label_score_len equ $ - label_score

    label_remark db "Remarks: "
    label_remark_len equ $ - label_remark

    excellent db "Excellent", 10
    excellent_len equ $ - excellent

    pass db "Pass", 10
    pass_len equ $ - pass

    fail db "Fail", 10
    fail_len equ $ - fail

    newline db 10

section .bss
    name resb 32
    score_input resb 4

section .text
    global _start

_start:

; -------------------------
; INPUT NAME
; -------------------------
    mov rax, 1
    mov rdi, 1
    mov rsi, prompt_name
    mov rdx, prompt_name_len
    syscall

    mov rax, 0
    mov rdi, 0
    mov rsi, name
    mov rdx, 32
    syscall
    mov r12, rax        ; store name length

; -------------------------
; INPUT SCORE
; -------------------------
    mov rax, 1
    mov rdi, 1
    mov rsi, prompt_score
    mov rdx, prompt_score_len
    syscall

    mov rax, 0
    mov rdi, 0
    mov rsi, score_input
    mov rdx, 4
    syscall

; -------------------------
; CONVERT SCORE (ASCII → INT)
; -------------------------
    mov rsi, score_input
    xor rax, rax        ; result = 0

convert:
    mov bl, [rsi]
    cmp bl, 10
    je done_convert

    sub bl, '0'
    imul rax, rax, 10
    movzx rbx, bl
    add rax, rbx

    inc rsi
    jmp convert

done_convert:
    mov r13, rax        ; store numeric score

; -------------------------
; DETERMINE REMARK
; -------------------------
    cmp r13, 90
    jge set_excellent

    cmp r13, 75
    jge set_pass

    jmp set_fail

set_excellent:
    mov r14, excellent
    mov r15, excellent_len
    jmp print_output

set_pass:
    mov r14, pass
    mov r15, pass_len
    jmp print_output

set_fail:
    mov r14, fail
    mov r15, fail_len

; -------------------------
; OUTPUT RESULT
; -------------------------
print_output:

    ; print "Name: "
    mov rax, 1
    mov rdi, 1
    mov rsi, label_name
    mov rdx, label_name_len
    syscall

    ; print name
    mov rax, 1
    mov rdi, 1
    mov rsi, name
    mov rdx, r12
    syscall

    ; print "Score: "
    mov rax, 1
    mov rdi, 1
    mov rsi, label_score
    mov rdx, label_score_len
    syscall

    ; NOTE: printing raw score input (string)
    mov rax, 1
    mov rdi, 1
    mov rsi, score_input
    mov rdx, 4
    syscall

    ; print newline
    mov rax, 1
    mov rdi, 1
    mov rsi, newline
    mov rdx, 1
    syscall

    ; print "Remarks: "
    mov rax, 1
    mov rdi, 1
    mov rsi, label_remark
    mov rdx, label_remark_len
    syscall

    ; print remark
    mov rax, 1
    mov rdi, 1
    mov rsi, r14
    mov rdx, r15
    syscall

; -------------------------
; EXIT
; -------------------------
    mov rax, 60
    xor rdi, rdi
    syscall

#NoEnv  ; Recommended for performance and compatibility with future AutoHotkey releases.
; #Warn  ; Enable warnings to assist with detecting common errors.
SendMode Input  ; Recommended for new scripts due to its superior speed and reliability.
SetWorkingDir %A_ScriptDir%  ; Ensures a consistent starting directory.

^1::Send {f1}
^2::SendInput {f2}
^3::SendInput {f3}
^4::SendInput {f4}
^5::SendInput {f5}
^6::SendInput {f6}
^7::SendInput {f7}
^8::SendInput {f8}
^9::SendInput {f9}
^0::SendInput {f10}
^-::SendInput {f11}
^=::SendInput {f12}
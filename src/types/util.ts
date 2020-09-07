export class UnreachableCodeException extends Error {}

export function unreachableCode(): never {
    throw new UnreachableCodeException()
}

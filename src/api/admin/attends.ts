import Axios from 'axios'

export function deleteAttend({ id }: { id: string }) {
    return Axios.delete(`/api/admin/attends/${id}`)
}

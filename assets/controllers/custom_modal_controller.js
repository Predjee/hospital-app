import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["backdrop", "form"]

    connect() {
        this.element.addEventListener("turbo:submit-end", (event) => {
            if (event.detail.success) {
                this.close()
                this.resetForm()
            }
        })
    }

    open() {
        this.backdropTarget.classList.remove("hidden")
        document.body.classList.add("overflow-hidden")
    }

    close() {
        this.backdropTarget.classList.add("hidden")
        document.body.classList.remove("overflow-hidden")
    }

    resetForm() {
        if (this.hasFormTarget) {
            this.formTarget.reset()
        }
    }
}

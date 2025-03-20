panel.plugin("elliott/ssh-deploy", {
  fields: {
    sshDeploy: {
      props: {
        label: String,
        progress: String,
        success: String,
        error: String
      },
      data: function() {
        return {
          isLoading: false,
          status: null,
          message: null
        };
      },
      template: `
        <k-field :label="label" class="k-ssh-deploy-field">
          <div>
            <k-button 
              :disabled="isLoading" 
              :icon="isLoading ? 'loader' : 'upload'" 
              :text="isLoading ? progress : 'Deploy'" 
              @click="deploy" 
              theme="positive" 
            />
            
            <k-box v-if="status === 'success'" theme="positive" class="k-ssh-deploy-message">
              <k-icon type="check" />
              {{ message || success }}
            </k-box>
            
            <k-box v-if="status === 'error'" theme="negative" class="k-ssh-deploy-message">
              <k-icon type="alert" />
              {{ message || error }}
            </k-box>
          </div>
        </k-field>
      `,
      methods: {
        deploy() {
          this.isLoading = true;
          this.status = null;
          this.message = null;
          
          this.$api.post('ssh-deploy')
            .then(response => {
              this.isLoading = false;
              this.status = response.status;
              this.message = response.message;
            })
            .catch(error => {
              this.isLoading = false;
              this.status = 'error';
              this.message = error.message || 'An unknown error occurred';
            });
        }
      }
    }
  }
}); 
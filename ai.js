jQuery(document).ready(function($) {
    $('#automated-accessibility-checker-analyze').on('click', function() {
  
      const content = wp.data.select('core/editor').getEditedPostContent();
      if (content.length === 0) {
        alert('Please enter some content to analyze.');
        return;
      }
  
      // Use the API key from the config file
      const openAIApiKey = automated_accessibility_checker.openai_api_key;
  
      $.ajax({
        url: 'https://api.openai.com/v1/engines/davinci/completions',
        method: 'POST',
        beforeSend: function(xhr) {
          xhr.setRequestHeader('Authorization', 'Bearer ' + openAIApiKey);
        },
        data: JSON.stringify({
          prompt: 'Please provide suggestions to improve the accessibility of the following content according to WCAG guidelines, without including any active links: ' + content,
          max_tokens: 150,
          n: 3, // Request 3 suggestions
        }),
        contentType: 'application/json',
        success: function(response) {
          console.log('Success:', response); // Debugging line
          const suggestions = response.choices.map((choice, index) => `<p><strong>Suggestion ${index + 1}:</strong> ${choice.text.trim()}</p>`).join('');
          $('#automated-accessibility-checker-results').html('<p>Suggestions:</p>' + suggestions);
        },    
        error: function(jqXHR, textStatus, errorThrown) {
          console.log('Error:', textStatus, errorThrown); // Debugging line
          alert('An error occurred. Please try again.');
        }
      });     
    });
  });
  
import sys
import json
from genderdecoder import assess

def check_gender_bias(text):
    result = assess(text)
    
    # Rename the results for better clarity
    if result['result'] == 'strongly masculine-coded':
        result['result'] = "Strong Male Bias"
    elif result['result'] == 'masculine-coded':
        result['result'] = "Male Bias"
    elif result['result'] == 'strongly feminine-coded':
        result['result'] = "Strong Female Bias"
    elif result['result'] == 'feminine-coded':
        result['result'] = "Female Bias"
    else:
        result['result'] = "Neutral"
    
    return result

if __name__ == "__main__":
    if len(sys.argv) > 1:
        text = sys.argv[1]
        output = check_gender_bias(text)
        print(json.dumps(output))
    else:
        print("Please provide a text as an argument.")
